<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
/* @var $roles array */

$userId = $model->getId();
$userRoles = array_keys(Yii::$app->authManager->getRolesByUser($userId));
$userRoleName = (isset($userRoles[0])) ? $userRoles[0] : '';
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= ($model->isNewRecord) ? $form->field($model, 'password')->passwordInput(['value' => '']) : '' ?>

    <?= HTML::label('Roles') ?>
    <?= Html::dropDownList('role', $userRoleName, $roles, ['class' => 'form-group form-control']) ?>

    <div class="form-group">
        <?=
        Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
        ])
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
