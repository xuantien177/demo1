<?php

namespace common\models;

use backend\models\QuanLyPhanQuyen;
use backend\models\VaiTro;
use backend\models\Vaitrouser;
use Faker\Provider\cs_CZ\DateTime;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use Yii;
use yii\bootstrap\Html;
use yii\jui\DatePicker;
use yii\web\HttpException;
use yii\web\View;
use yii\widgets\ActiveForm;

class myAPI
{
    const ACTIVE = 1;
    const IN_ACTIVE = 0;

    public static function createCode($str){
        $str = trim($str);
        $coDau=array("à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă","ằ","ắ"
        ,"ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề","ế","ệ","ể","ễ","ì","í","ị","ỉ","ĩ",
            "ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ"
        ,"ờ","ớ","ợ","ở","ỡ",
            "ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
            "ỳ","ý","ỵ","ỷ","ỹ",
            "đ",
            "À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă"
        ,"Ằ","Ắ","Ặ","Ẳ","Ẵ",
            "È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
            "Ì","Í","Ị","Ỉ","Ĩ",
            "Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ"
        ,"Ờ","Ớ","Ợ","Ở","Ỡ",
            "Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
            "Ỳ","Ý","Ỵ","Ỷ","Ỹ",
            "Đ","ê","ù","à");
        $khongDau=array("a","a","a","a","a","a","a","a","a","a","a"
        ,"a","a","a","a","a","a",
            "e","e","e","e","e","e","e","e","e","e","e",
            "i","i","i","i","i",
            "o","o","o","o","o","o","o","o","o","o","o","o"
        ,"o","o","o","o","o",
            "u","u","u","u","u","u","u","u","u","u","u",
            "y","y","y","y","y",
            "d",
            "A","A","A","A","A","A","A","A","A","A","A","A"
        ,"A","A","A","A","A",
            "E","E","E","E","E","E","E","E","E","E","E",
            "I","I","I","I","I",
            "O","O","O","O","O","O","O","O","O","O","O","O"
        ,"O","O","O","O","O",
            "U","U","U","U","U","U","U","U","U","U","U",
            "Y","Y","Y","Y","Y",
            "D","e","u","a");
        $str = str_replace($coDau,$khongDau,$str);
        $str = trim(preg_replace("/\\s+/", " ", $str));
        $str = preg_replace("/[^a-zA-Z0-9 \-\.]/", "", $str);
        $str = strtolower($str);
        return str_replace(" ", '-', $str);
    }

    public static function convertDMYtoYMD($date)
    {
        if ($date == '')
            return null;
        else {
            $arr = explode('/', $date);
            $arr = array_reverse($arr);
            return implode('-', $arr);
        }
    }
    public static function duyetNhom($object,$parentid = 0,$space = '--', $trees = NULL){
        if(!$trees) $trees = array();
        $nhoms = $object::find()->where(['parent_id' => $parentid])->all();
        foreach ($nhoms as $nhom) {
            $trees[] = array('id'=>$nhom->id,'title'=>$space.$nhom->name);
            $trees = myAPI::duyetNhom($object,$nhom->id,"|..".$space,$trees);
        }

        return $trees;
    }

    public static function dsNhom($object){
        $danhmuccons =$object::find()->where('parent_id is null')->all();
        $trees = array();
        foreach ($danhmuccons as $danhmuccon) {
            $trees[] = array('id'=>$danhmuccon->id, 'title'=>$danhmuccon->name);
            $trees = myAPI::duyetNhom($object,$danhmuccon->id,'|--',$trees);
        }
        return $trees;
    }

    public static function dataTree($object, $parentid,$trees){
        $trees =[];
        $danhmuccons = $object::find()->where(['parent_id' => $parentid])->all();
        foreach ($danhmuccons as $danhmuccon) {
            $nodes =[];
            $nodes = myAPI::dataTree($object,$danhmuccon->id,$nodes);
            $trees[] = ['id' => $danhmuccon->id, 'title' => $danhmuccon->name, 'nodes' => $nodes];
        }
        return $trees;
    }

    public static function getNam($namBatDau,$namKetThuc){
        $namBatDau = (int)$namBatDau;
        $namKetThuc = (int)$namKetThuc;
        for($i=$namBatDau;$i <= $namKetThuc;$i++)
        {
            $data[$i] = $i;
        }
        return $data;
    }

    public static function getCapDo($str = 'quan | huyen | phuong | xa | thitran'){
        $data = [
            'quan' => 'Quận',
            'huyen' => 'Huyện',
            'phuong' => 'Phường',
            'xa' => 'Xã',
            'thitran' => 'Thị trấn'
        ];
        return $data[$str];
    }

    public static function getTab($cap = 'quan | huyen | xa | phuong | thitran' ){
        $data = [
            'quan' => 0,
            'huyen' => 0,
            'phuong' => 5,
            'xa' => 5,
            'thitran' => 5
        ];

        $str = '';
        for($i = 0; $i<=$data[$cap]; $i++)
            $str.='&emsp;';

        return $str;
    }

    public static function getMessage($att, $content){
        return "<div class='note note-{$att}'>{$content}</div>";
    }

    public static function isAccess($tenvaitro){
        return (new User())->isAccess($tenvaitro);
    }

    public static function getIdOtherModel($value, $model, $attributeTitle = 'name', $attributeType = ['name' => '', 'value' => '']){
        if(trim($value)=="")
            return new Expression('NULL');

        $data = $model->find()->where("code = :name", [':name' => self::createCode(trim($value))])->one();

        if(is_null($data)){
            $model->{$attributeTitle} = trim($value);
            if($attributeType['name'] != '')
                $model->{$attributeType['name']} = trim($attributeType['value']);

            $model->save();
            return $model->id;
        }
        return $data->id;
    }

    public static function getHeadModal($noidung){
        return '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">'.$noidung.'</h4>';
    }

    public static function activeDateField($form, $model, $field, $label, $yearRange = '1950:2050'){
        return $form->field($model,$field)->widget(DatePicker::className(),[
            'language' => 'vi',
            'dateFormat' => 'dd/MM/yyyy',
            'clientOptions' => [
                'changeMonth' => true,
                'yearRange' => $yearRange,
                'changeYear' => true,
            ],
            'options' => ['class' => 'form-control']
        ])->label($label);
    }

    public static function dateField($name, $value, $class='form-control', $yearRange = '1950:2050'){
        return DatePicker::widget([
            'language' => 'vi',
            'name' => $name,
            'value' => $value,
            'dateFormat' => 'dd/MM/yyyy',
            'clientOptions' => [
                'changeMonth' => true,
                'yearRange' => $yearRange,
                'changeYear' => true,
            ],
            'options' => ['class' => $class]
        ]);
    }

    public static function activeDateField2($form, $model, $field, $label, $yearRange = '1950:2050', $options = ['class' => 'form-control']){
        return $form->field($model,$field)->widget(DatePicker::className(),[
            'language' => 'vi',
            'dateFormat' => 'dd/MM/yyyy',
            'clientOptions' => [
                'changeMonth' => true,
                'yearRange' => $yearRange,
                'changeYear' => true,
            ],
            'options' => ['class' => 'form-control']
        ])->label($label);
    }

    public static function activeDateField3($form, $model,$name, $field, $label, $yearRange = '1950:2050', $options = ['class' => 'form-control']){
        return $form->field($model,$field)->widget(DatePicker::className(),[
            'name' => $name,
            'language' => 'vi',
            'dateFormat' => 'dd/MM/yyyy',
            'clientOptions' => [
                'changeMonth' => true,
                'yearRange' => $yearRange,
                'changeYear' => true,
            ],
            'options' => ['class' => 'form-control']
        ])->label($label);
    }

    public static function dateField2($name, $value, $options=[]){
        return DatePicker::widget([
            'language' => 'vi',
            'name' => $name,
            'value' => $value,
            'dateFormat' => 'dd/MM/yyyy',
            'clientOptions' => [
                'changeMonth' => true,
                'yearRange' => (date("Y")-10).':'.(date("Y") + 10),
                'changeYear' => true,
            ],
            'options' => array_merge($options, ['class' => 'form-control'])
        ]);
    }

    public static function convertDateSaveIntoDb($date, $splash = '/'){
        $date = trim($date);
        if($date == "")
            return new Expression('NULL');
        $arr = explode(trim($splash), $date);
        if(count($arr) == 3)
            return implode('-', array_reverse($arr));
        else if(count($arr) == 2)
            return date("Y")."-{$arr[1]}-{$arr[0]}";
        else {
            if(strpos($arr[0], '-')){
                return $arr[0];
            }else {
                return date("Y") . "-" . date("m") . "-" . $arr[0];
            }
        }
    }

    public static function convertDateFromDb($date, $splash = '-'){
        $arr  = explode(trim($splash), $date);
        $arr = array_reverse($arr);
        return $arr[0].'/'.$arr[1].'/'.$arr[2];
    }

    public static function getBtnCloseModal(){
        return Html::button('<i class="fa fa-close"></i> Đóng lại',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]);
    }

    public static function getBtnFooter( $label, $options = []){
        return Html::button($label, $options);
    }

    public static function getVaitro(){
        return [
            'Quản trị viên' => '<span class="text-danger"><i class="fa fa-flag"></i> Quản trị viên</span>',
            'Quản lý' => '<span class="text-warning"><i class="fa fa-flag"></i> Quản lý</span>',
            'Nhân viên' => '<span class="text-success"><i class="fa fa-flag"></i> Nhân viên</span>',
            'Chăm sóc' => '<span class="text-primary"><i class="fa fa-flag"></i> Chăm sóc</span>',
        ];
    }

    public static function getAFieldOfAModelFromName($model, $field, $name){
        $code = self::createCode(trim($name));
        $data = $model->find()->where(['code' => $code])->one();
        if(is_null($data))
            return '';
        return $data->{$field};
    }

    public static function getFilterFromTo($searchModel, $fieldFrom, $field_to){
        return Html::activeTextInput($searchModel, $fieldFrom, ['placeholder' => 'Từ...', 'class' => 'form-control']).
            Html::activeTextInput($searchModel, $field_to, ['placeholder' => 'Đến...', 'class' => 'form-control']);
    }

    public static function getBtnSearch(){
        return '<button type="button" class="btn blue btn-search"><i class="fa fa-search"></i> Tìm kiếm</button>';
    }

    public static function getThoiGian($denNgay, $tuNgay, $donvi){
        $datetime1 = new \DateTime($denNgay);
        $datetime2 = new \DateTime($tuNgay);
        $interval = $datetime1->diff($datetime2);
        $m = $interval->m;
        $y = $interval->y;

        if($donvi == 'y')
            return $y;
        return $y*12 + $m;
    }

    public static function congThang($ngaythang, $sothang, $format="Y-m-d"){
        $effectiveDate = date($format, strtotime("+{$sothang} months", strtotime($ngaythang))); // returns timestamp
        return $effectiveDate;
    }

    public static function getBtnDownload(){
        return Html::button('<i class="fa fa-cloud-download"></i> Tải xuống',['class'=>'btn btn-primary btn-download-ketquatimkiem pull-right']);
    }

    public static function registerFileJS($view, $file){
        $view->registerJsFile(Yii::$app->request->baseUrl.'/backend/themes/qltk2/assets/global/'.$file,[ 'depends' => ['backend\assets\Qltk2Asset'], 'position' => \yii\web\View::POS_END ]);
    }

    public static function getDMY($strYMD){
        if($strYMD != '')
            return date("d/m/Y", strtotime($strYMD));
        return '';
    }

    public static function isAccess2($controller, $action){
        if(Yii::$app->user->isGuest)
            return false;
        else{
            if(Yii::$app->user->identity->getId() == 1)
                return true;
            $action = ucfirst($action);
            $controller_action = "{$controller};{$action}";
            $user_id = Yii::$app->user->id;
            return !is_null(QuanLyPhanQuyen::findOne(['controller_action' => $controller_action, 'user_id' => $user_id]));
        }
//        return true;
    }

    public static function isViewAll(){
        $user_vaitro = User::find()->andFilterWhere(['id' => Yii::$app->user->id])
            ->andFilterWhere(['status' => 10])
            ->andFilterWhere(['in', 'vaitro', [VaiTro::QUAN_LY, VaiTro::QUAN_TRI_VIEN]])
            ->one();
        return (Yii::$app->user->id == 1 || !is_null($user_vaitro));
    }

    public static function createDeleteBtnInGrid($path, $title = 'Hủy dữ liệu'){
        return Html::a('<i class="fa fa-trash"></i>', $path,['title' => $title, 'data-pjax' => 0, 'role' => 'modal-remote', 'data-request-method' => 'post', 'data-toggle' => 'tooltip', 'data-confirm-title' => 'Thông báo', 'data-confirm-message' => 'Bạn có chắc chắn muốn hủy dữ liệu này?', 'data-original-title' => 'Hủy', 'class' => 'text-danger']);
    }

    public static function activeDateFieldNoLabel($model, $attribute, $yearRange = '2015:2100', $options = ['class' => 'form-control']){
        return DatePicker::widget([
            'language' => 'vi',
            'model' => $model,
            'dateFormat' => 'dd/MM/yyyy',
            'attribute' => $attribute,
            'clientOptions' => [
                'changeMonth' => true,
                'yearRange' => $yearRange,
                'changeYear' => true,
            ],
            'options' => $options
        ]);
    }

    public static function isAccess3(){
        if(Yii::$app->user->identity->getId() == 1)
            return true;

        $vaiTroUser = ArrayHelper::map(Vaitrouser::findAll(['user_id' => Yii::$app->user->id]), 'id', function ($model){
            /** @var $model Vaitrouser */
            return $model->vaitro0->name;
        });

        if(in_array(VaiTro::QUAN_LY, $vaiTroUser)){
            return true;
        }

        return false;
    }

    public static function errorSyntax(){
        throw new HttpException(500, 'Đường dẫn sai cú pháp');
    }

    public static function errorData(){
        throw new HttpException(500, 'Không xác thực được dữ liệu');
    }

    public static function getListActive(){
        return [
            self::ACTIVE => 'Kích hoạt',
            self::IN_ACTIVE => 'Không kích hoạt',
        ];
    }

    public static function get_extension($imagetype)
    {
        if(empty($imagetype)) return false;
        switch($imagetype)
        {
            case 'image/bmp': return '.bmp';
            case 'image/cis-cod': return '.cod';
            case 'image/gif': return '.gif';
            case 'image/ief': return '.ief';
            case 'image/jpeg': return '.jpg';
            case 'image/pipeg': return '.jfif';
            case 'image/tiff': return '.tif';
            case 'image/x-cmu-raster': return '.ras';
            case 'image/x-cmx': return '.cmx';
            case 'image/x-icon': return '.ico';
            case 'image/x-portable-anymap': return '.pnm';
            case 'image/x-portable-bitmap': return '.pbm';
            case 'image/x-portable-graymap': return '.pgm';
            case 'image/x-portable-pixmap': return '.ppm';
            case 'image/x-rgb': return '.rgb';
            case 'image/x-xbitmap': return '.xbm';
            case 'image/x-xpixmap': return '.xpm';
            case 'image/x-xwindowdump': return '.xwd';
            case 'image/png': return '.png';
            case 'image/x-jps': return '.jps';
            case 'image/x-freehand': return '.fh';
            default: return false;
        }
    }
}