<?php

use yii\db\Migration;

class m170809_185634_add_admin_user extends Migration
{
    public function up()
    {
        $this->insert('user', [
            'email'    => 'admin@admin.com',
            'username' => 'Admin',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password' => Yii::$app->getSecurity()->generatePasswordHash('123'),
            'type'     => 'admin'
        ]);
    }

    public function down()
    {
        return false;
    }
}
