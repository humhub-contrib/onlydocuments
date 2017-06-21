<?php
use yii\db\Migration;
class uninstall extends Migration
{
    public function up()
    {
        $this->dropTable('onlydocuments_share');
        $this->dropColumn('file', 'onlydocuments_key');
    }
    public function down()
    {
        echo "uninstall does not support migration down.\n";
        return false;
    }
}