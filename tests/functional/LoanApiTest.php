<?php

namespace tests\functional;

use PHPUnit\Framework\TestCase;
use yii\web\Application;

class LoanApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Clean database before each test
        \Yii::$app->db->createCommand('TRUNCATE loan_requests RESTART IDENTITY')->execute();
    }
    
    public function testCreateLoanRequestReturns201()
    {
        $data = [
            'user_id' => 1,
            'amount' => 3000,
            'term' => 30
        ];
        
        $response = $this->makeRequest('POST', '/requests', $data);
        
        $this->assertEquals(201, $response['status']);
        $this->assertTrue($response['data']['result']);
        $this->assertIsInt($response['data']['id']);
    }
    
    public function testCreateLoanRequestWithInvalidDataReturns400()
    {
        $data = [
            'user_id' => 1,
            'amount' => 0, // Invalid
            'term' => 30
        ];
        
        $response = $this->makeRequest('POST', '/requests', $data);
        
        $this->assertEquals(400, $response['status']);
        $this->assertFalse($response['data']['result']);
    }
    
    public function testProcessorReturns200()
    {
        $response = $this->makeRequest('GET', '/processor?delay=1');
        
        $this->assertEquals(200, $response['status']);
        $this->assertTrue($response['data']['result']);
    }
    
    public function testProcessorProcessesPendingRequests()
    {
        // Create a pending request first
        $this->makeRequest('POST', '/requests', [
            'user_id' => 1,
            'amount' => 3000,
            'term' => 30
        ]);
        
        // Process it
        $this->makeRequest('GET', '/processor?delay=1');
        
        // Check it was processed
        $request = \app\models\LoanRequest::findOne(1);
        $this->assertNotEquals('pending', $request->status);
        $this->assertContains($request->status, ['approved', 'declined']);
    }
    
    private function makeRequest($method, $path, $data = [])
    {
        // Directly test the controller actions
        $controller = new \app\controllers\LoanController('loan', \Yii::$app);
        
        // Mock the request
        $request = \Yii::$app->request;
        $request->setIsConsoleRequest(false);
        $request->setUrl($path);
        
        // Set method and data
        $_SERVER['REQUEST_METHOD'] = $method;
        if ($method === 'POST' && !empty($data)) {
            $request->setBodyParams($data);
        } elseif ($method === 'GET' && !empty($data)) {
            $request->setQueryParams($data);
        }
        
        // Create response
        $response = new \yii\web\Response();
        \Yii::$app->set('response', $response);
        
        // Call the appropriate action
        if ($path === '/requests') {
            $result = $controller->actionCreate();
        } elseif (strpos($path, '/processor') === 0) {
            $query = parse_url($path, PHP_URL_QUERY);
            parse_str($query, $params);
            $result = $controller->actionProcess();
        } else {
            throw new \Exception("Unknown route: $path");
        }
        
        return [
            'status' => $response->statusCode,
            'data' => $result
        ];
    }
}
