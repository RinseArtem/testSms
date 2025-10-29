<?php
namespace app\services;

use app\interfaces\SmsProviderInterface;
use app\models\SmsCode;
use DateTime;

class SmsService
{
    private SmsProviderInterface $provider;

    public function __construct(SmsProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function sendVerificationCode($phone, $uuid): string
    {
        $code = random_int(1000, 9999);


        $message = "Ваш код: $code";
        if (!$this->provider->send($phone, $message)) {
            // Сообщение отправлено успешно
        }

        $smsCode = new SmsCode([
            'phone' => $phone,
            'code' => $code,
            'uuid' => $uuid,
        ]);

        $smsCode->save();

        return $code;

    }

    public function verifyCode($phone, $code): bool
    {
        $smsCode = SmsCode::find()
            ->where(['phone' => $phone, 'code' => $code])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();

        if ($smsCode && (time() - strtotime($smsCode->created_at) < 600)) {
            $smsCode->is_verified = true;
            $smsCode->save();
            return true;
        }


        return false;
    }

    function getSendingDelayByUuid(string $uuid): int
    {
        $debounceSeconds = 0;

        $lastCode = SmsCode::find()->where(['uuid' => $uuid])->orderBy(['created_at' => SORT_DESC])->one();

        if ($lastCode) {
            $debounceSeconds = strtotime($lastCode->created_at) - time() + 60;
        }

        return max($debounceSeconds, 0);
    }
}
