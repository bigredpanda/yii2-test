<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
$isAdmin = Yii::$app->user->can('admin');
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= ($isAdmin) ? Html::a('Create User', ['create'], ['class' => 'btn btn-success', '']) : '' ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            'email',
            [
                'class'          => 'yii\grid\ActionColumn',
                'header'         => 'Actions',
                'template'       => '{view} {update} {delete}',
                'visibleButtons' => [
                    'update' => $isAdmin,
                    'delete' => $isAdmin
                ]
            ],
        ],
    ]);
    ?>
</div>
