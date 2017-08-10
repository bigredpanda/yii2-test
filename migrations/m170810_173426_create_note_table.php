<?php

use yii\db\Migration;

/**
 * Handles the creation of table `note`.
 */
class m170810_173426_create_note_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('note', [
            'id'      => $this->primaryKey(),
            'title'   => $this->string(64)->notNull(),
            'message' => $this->string(255)->notNull(),
            'author'  => $this->integer()->notNull()
        ]);

        $this->addForeignKey('user', 'note', 'author', 'user', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('note');
    }
}
