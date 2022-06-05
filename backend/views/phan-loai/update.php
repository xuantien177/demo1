<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PhanLoai */

$this->title = 'Cập nhật danh mục: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Phân Loại Sản Phẩm', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Cập Nhật';
?>
<div class="phan-loai-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
