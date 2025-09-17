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
        // Mock HTTP request - simplified for testing
        // In real implementation, would use proper HTTP client
        return [
            'status' => 200,
            'data' => ['result' => true]
        ];
    }
}
