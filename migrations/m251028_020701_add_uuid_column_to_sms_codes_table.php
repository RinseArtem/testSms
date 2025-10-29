<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%sms_codes}}`.
 */
class m251028_020701_add_uuid_column_to_sms_codes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sms_codes', 'uuid', $this->string(36)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
