<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Image $model
 */

$this->title = '添加图片';
$this->params['breadcrumbs'][] = ['label' => '背景图片管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
