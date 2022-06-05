<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PhanLoai */

$this->title = 'Tạo Phân Loại';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý phân loại', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phan-loai-create">

    <h3 class="text-center text-info"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
