<?php

namespace app\controllers;

use app\models\LoanRequest;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use Yii;

class LoanController extends Controller
{
    public $enableCsrfValidation = false; // Disable CSRF for API
    
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['POST'],
                    'process' => ['GET'],
                ],
            ],
        ];
    }
    
    /**
     * POST /requests - Submit loan request
     */
    public function actionCreate()
    {
        $request = new LoanRequest();
        $request->scenario = 'create';
        
        if ($request->load(Yii::$app->request->post(), '') && $request->save()) {
            Yii::$app->response->statusCode = 201;
            return [
                'result' => true,
                'id' => $request->id
            ];
        }
        
        Yii::$app->response->statusCode = 400;
        return ['result' => false];
    }
    
    /**
     * GET /processor - Process pending requests
     */
    public function actionProcess()
    {
        $delay = (int) Yii::$app->request->get('delay', 1);
        $pendingRequests = LoanRequest::getPendingRequests();
        
        foreach ($pendingRequests as $request) {
            $this->processRequest($request, $delay);
        }
        
        return ['result' => true];
    }
    
    private function processRequest(LoanRequest $request, int $delay)
    {
        // Simulate processing delay
        sleep($delay);
        
        // 10% approval rate
        $isApproved = (rand(1, 100) <= 10);
        
        if ($isApproved && !$this->userHasApprovedLoan($request->user_id)) {
            $request->approve();
        } else {
            $request->decline();
        }
    }
    
    private function userHasApprovedLoan(int $userId): bool
    {
        return LoanRequest::find()
            ->where(['user_id' => $userId, 'status' => LoanRequest::STATUS_APPROVED])
            ->exists();
    }
}
