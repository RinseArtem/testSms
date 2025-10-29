<?php

use yii\db\Migration;

class m251028_013217_sms_codes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('sms_codes', [
            'id' => $this->primaryKey(),
            'phone' => $this->string(19)->notNull(),
            'code' => $this->integer(4)->notNull(),
            'is_verified' => $this->boolean()->notNull()->defaultValue(0),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251028_013217_sms_codes cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251028_013217_sms_codes cannot be reverted.\n";

        return false;
    }
    */
}
