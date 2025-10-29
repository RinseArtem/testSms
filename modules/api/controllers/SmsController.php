<?php
namespace app\modules\api\controllers;

use app\models\SmsCode;
use app\services\SmsService;
use Yii;
use yii\rest\Controller;
use yii\rest\OptionsAction;
use yii\web\Response;
use yii\filters\Cors;

class SmsController extends Controller
{
    private SmsService $_sms;


    public function __construct($id, $module, SmsService $sms, $config = [])
    {
        $this->_sms = $sms;
        parent::__construct($id, $module, $config);
    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['http://localhost:3000'],
                'Access-Control-Allow-Origin' => ['http://localhost:3000'],
                'Access-Control-Allow-Headers' => [
                    'Content-Type', 'Authorization', 'X-Requested-With', 'Accept', 'Origin'
                ],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        $behaviors['contentNegotiator']['formats'] = [
            'application/json' => Response::FORMAT_JSON,
        ];

        return $behaviors;
    }

    // POST /api/sms/send
    public function actionSend()
    {
        $phone = Yii::$app->request->getBodyParam('phone');
        $uuid = Yii::$app->request->getBodyParam('uuid');

        $delay = $this->_sms->getSendingDelayByUuid($uuid);
        if ($delay > 0) {
            Yii::$app->response->statusCode = 429;
            return $this->asJson([
                'error' => 'You can send no more than one message per minute.',
            ]);
        }

        $code = $this->_sms->sendVerificationCode($phone, $uuid);

        return $this->asJson([
            'success' => true,
            'data' => [
                'code'=> $code,
            ]
        ]);
    }

    public function actionVerifyCode()
    {
        $phone = Yii::$app->request->getBodyParam('phone');
        $code = Yii::$app->request->getBodyParam('code');

        if ($this->_sms->verifyCode($phone, $code)) {
            return $this->asJson([
                'success' => true,
                'data' => []
            ]);
        }

        Yii::$app->response->statusCode = 400;
        return $this->asJson([
            'error' => 'Invalid code',
        ]);

    }

    public function actionGetSendingDelay()
    {
        $uuid = Yii::$app->request->getBodyParam('uuid');

        return $this->asJson([
            'success' => true,
            'data' => [
                'delay' => $this->_sms->getSendingDelayByUuid($uuid),
            ]
        ]);
    }

}
