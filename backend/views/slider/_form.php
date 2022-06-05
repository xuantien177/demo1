<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Slider */
/* @var $form yii\widgets\ActiveForm */
/* @var $hinh_anh_slider \common\models\AnhSlider[]*/

$string =  '<!DOCTYPE html>'.
    '<html lang="en">'.
    '<head>'.
    '  <title>Bootstrap Example</title>'.
    '  <meta charset="utf-8">'.
    '  <meta name="viewport" content="width=device-width, initial-scale=1">'.
    '  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">'.
    '  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous"></head>'.
    '  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>'.
    '  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>'.
    '  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>'.
    '<body>'.
    '</div>'.
    '</body>'.
    '</html>';

echo $string;
?>

<div class="slider-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'enctype'=>'multipart/form-data'
        ]
    ]); ?>

    <?= $form->field($model, 'caption')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tom_tat')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hinh_anhs[]')->fileInput(['multiple' => 'multiple']) ?>

    <?php if (!$model->isNewRecord):?>
        <div class='row'>
            <?php foreach ($hinh_anh_slider as $item):?>
                <div class='col-md-2'>
                    <?=Html::img('../images/'.$item->file,['width'=>'150px'])?>
                    <p><?=Html::a('<i class="fa fa-trash" aria-hidden="true"></i> Xoá',\yii\helpers\Url::toRoute(['slider/xoa-anh-slider','idhinhanh'=>$item->id]),['class'=>'btn btn-sm btn-danger'])?></p>

                </div>
            <?php endforeach;?>
        </div>
    <?php endif;?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
