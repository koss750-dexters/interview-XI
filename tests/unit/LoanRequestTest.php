<?php

namespace tests\unit;

use app\models\LoanRequest;
use PHPUnit\Framework\TestCase;

class LoanRequestTest extends TestCase
{
    public function testValidationPasses()
    {
        $request = new LoanRequest([
            'user_id' => 1,
            'amount' => 3000,
            'term' => 30
        ]);
        
        $this->assertTrue($request->validate());
    }
    
    public function testValidationFailsWithInvalidAmount()
    {
        $request = new LoanRequest([
            'user_id' => 1,
            'amount' => 0,
            'term' => 30
        ]);
        
        $this->assertFalse($request->validate());
        $this->assertArrayHasKey('amount', $request->errors);
    }
    
    public function testValidationFailsWithInvalidTerm()
    {
        $request = new LoanRequest([
            'user_id' => 1,
            'amount' => 3000,
            'term' => 0
        ]);
        
        $this->assertFalse($request->validate());
        $this->assertArrayHasKey('term', $request->errors);
    }
    
    public function testValidationFailsWithInvalidUserId()
    {
        $request = new LoanRequest([
            'user_id' => 0,
            'amount' => 3000,
            'term' => 30
        ]);
        
        $this->assertFalse($request->validate());
        $this->assertArrayHasKey('user_id', $request->errors);
    }
    
    public function testDefaultStatusIsPending()
    {
        $request = new LoanRequest();
        $this->assertEquals('pending', $request->status);
    }
}
