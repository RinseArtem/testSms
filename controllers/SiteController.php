<?php

namespace app\controllers;

use app\services\SmsService;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;


class SiteController extends Controller
{
    /**
     * @throws InvalidConfigException
     */
    function actionIndex(): string
    {
        $smsService = Yii::createObject(SmsService::class);
        return $this->renderContent('Hello');
    }
}
