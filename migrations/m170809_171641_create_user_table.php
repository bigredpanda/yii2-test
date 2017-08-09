<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m170809_171641_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', [
            'id'       => $this->primaryKey(),
            'email'    => $this->string(32)->unique()->notNull(),
            'auth_key' => $this->string(),
            'username' => $this->string(32)->unique()->notNull(),
            'password' => $this->string(128)->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
