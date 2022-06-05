<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

$string = '<!DOCTYPE html>' .
    '<html lang="en">' .
    '<head>' .
    '  <title>Bootstrap Example</title>' .
    '  <meta charset="utf-8">' .
    '  <meta name="viewport" content="width=device-width, initial-scale=1">' .
    '  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">' .
    '  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous"></head>' .
    '  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>' .
    '  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>' .
    '  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>' .

    '</html>';

echo $string;


/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PhanLoaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý danh mục phân loại';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phan-loai-index">

    <h3 class="text-info text-center"><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('<i class="fas fa-plus"></i>Tạo Phân Loại', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'code',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, \common\models\PhanLoai $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
