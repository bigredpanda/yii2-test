<?php

use app\models\User;
use yii\db\Migration;

class m170809_185634_add_admin_user extends Migration
{
    public function up()
    {
        $user = new User();
        $user->email = 'admin@admin.com';
        $user->username = 'Admin';
        $user->setPassword('123');
        $user->generateAuthKey();
        $user->save();

        $auth = Yii::$app->authManager;
        $adminRole = $auth->getRole('admin');

        $auth->assign($adminRole, $user->getId());
    }

    public function down()
    {
        return false;
    }
}
