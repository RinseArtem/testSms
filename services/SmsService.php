<?php
class SmsService
{
    private SmsProviderInterface $provider;

    public function __construct(SmsProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function sendVerificationCode($phone): string
    {
        $code = random_int(1000, 9999);

        // Проверка ограничения по времени (1 SMS/60 сек) — пример:
        $lastCode = SmsCode::find()->where(['phone' => $phone])->orderBy(['created_at' => SORT_DESC])->one();
        if ($lastCode && (time() - strtotime($lastCode->created_at) < 60)) {
            throw new \Exception('Можно отправлять не чаще чем раз в 60 секунд');
        }

        $message = "Ваш код: $code";
        if (!$this->provider->send($phone, $message)) {
            throw new \Exception('Ошибка отправки SMS');
        }

        $smsCode = new SmsCode([
            'phone' => $phone,
            'code' => $code,
            'created_at' => date('Y-m-d H:i:s')
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

        return $smsCode && (time() - strtotime($smsCode->created_at) < 600); // 10 минут
    }

    private function canSendSms($phone): bool
    {

    }
}
