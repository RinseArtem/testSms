<?php
interface SmsProviderInterface
{
    public function send(string $phone, string $message): bool;
}
