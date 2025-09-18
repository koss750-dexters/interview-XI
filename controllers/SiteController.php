<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    public function actionIndex()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        return [
            'message' => 'Loan Application API',
            'version' => '1.0.0',
            'endpoints' => [
                'POST /requests' => 'Submit loan request',
                'GET /processor?delay=N' => 'Process pending requests with delay'
            ],
            'example' => [
                'method' => 'POST',
                'url' => '/requests',
                'body' => [
                    'user_id' => 1,
                    'amount' => 3000,
                    'term' => 30
                ]
            ]
        ];
    }
}
