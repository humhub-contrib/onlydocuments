<?php

use yii\db\Migration;

class m170613_101409_file_oo_key extends Migration
{
    public function up()
    {
        $this->addColumn('file', 'onlydocuments_key', $this->char(20));
    }

    public function down()
    {
        echo "m170613_101409_file_oo_key cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
