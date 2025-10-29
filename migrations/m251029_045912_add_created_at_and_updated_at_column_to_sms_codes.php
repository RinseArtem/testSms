<?php

use yii\db\Migration;

class m251029_045912_add_created_at_and_updated_at_column_to_sms_codes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sms_codes', 'created_at', $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'));
        $this->addColumn('sms_codes', 'updated_at', $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251029_045912_add_created_at_and_updated_at_column_to_sms_codes cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251029_045912_add_created_at_and_updated_at_column_to_sms_codes cannot be reverted.\n";

        return false;
    }
    */
}
