<?php
class SmsCode extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'sms_code';
    }
}
