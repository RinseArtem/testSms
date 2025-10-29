<?php
namespace app\providers;
use app\interfaces\SmsProviderInterface;

class MySmsProvider implements SmsProviderInterface {

    public function send(string $phone, string $message): bool
    {
        return  true;
    }
}