<?php

use yii\db\Migration;

class m170809_171829_addcolumnto_table_user extends Migration
{

    public function up()
    {
        $this->addColumn('user', 'type', 'enum("student", "teacher", "admin") DEFAULT "student" NOT NULL');
    }

    public function down()
    {
        return false;
    }
}

