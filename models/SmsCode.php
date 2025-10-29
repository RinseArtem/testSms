<?php
namespace app\models;

use yii\db\ActiveRecord;

class SmsCode extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'sms_codes';
    }
}
