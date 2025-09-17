<?php

use yii\db\Migration;

class m240917_000001_create_loan_requests_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('loan_requests', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->integer()->notNull(),
            'term' => $this->integer()->notNull(),
            'status' => $this->string(20)->defaultValue('pending'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'processed_at' => $this->timestamp()->null(),
        ]);
        
        // Add constraints
        $this->addCheck('chk_amount_positive', 'loan_requests', 'amount > 0');
        $this->addCheck('chk_term_positive', 'loan_requests', 'term > 0');
        $this->addCheck('chk_status_valid', 'loan_requests', "status IN ('pending', 'approved', 'declined')");
        
        // Add indexes
        $this->createIndex('idx_user_status', 'loan_requests', ['user_id', 'status']);
        $this->createIndex('idx_status_created', 'loan_requests', ['status', 'created_at']);
    }
    
    public function safeDown()
    {
        $this->dropTable('loan_requests');
    }
}
