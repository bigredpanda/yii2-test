<?php

use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="note-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Note', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'message',
            'author' => [
                'header' => 'Author',
                'value'  => function ($model) {
                    $author = User::findOne($model->author);

                    return $author ? $author['username'] : '';
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
