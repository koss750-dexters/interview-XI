<?php

use yii\db\Migration;

class m240917_000002_setup_test_database extends Migration
{
    public function safeUp()
    {
        // Connect to test database and create the same table structure
        $testDb = new \yii\db\Connection([
            'dsn' => 'pgsql:host=postgres;dbname=loans_test',
            'username' => 'user',
            'password' => 'password',
        ]);
        
        // Check if table already exists in test database
        $schema = $testDb->schema;
        if ($schema->getTableSchema('loan_requests') === null) {
            // Create the same table in test database
            $testDb->createCommand("
                CREATE TABLE loan_requests (
                    id SERIAL PRIMARY KEY,
                    user_id INTEGER NOT NULL,
                    amount INTEGER NOT NULL,
                    term INTEGER NOT NULL,
                    status VARCHAR(20) DEFAULT 'pending',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    processed_at TIMESTAMP NULL
                )
            ")->execute();
            
            // Add constraints to test database
            $testDb->createCommand("
                ALTER TABLE loan_requests 
                ADD CONSTRAINT chk_amount_positive CHECK (amount > 0)
            ")->execute();
            
            $testDb->createCommand("
                ALTER TABLE loan_requests 
                ADD CONSTRAINT chk_term_positive CHECK (term > 0)
            ")->execute();
            
            $testDb->createCommand("
                ALTER TABLE loan_requests 
                ADD CONSTRAINT chk_status_valid CHECK (status IN ('pending', 'approved', 'declined'))
            ")->execute();
            
            // Add indexes to test database
            $testDb->createCommand("
                CREATE INDEX idx_user_status ON loan_requests (user_id, status)
            ")->execute();
            
            $testDb->createCommand("
                CREATE INDEX idx_status_created ON loan_requests (status, created_at)
            ")->execute();
            
            echo "Test database table 'loan_requests' created successfully.\n";
        } else {
            echo "Test database table 'loan_requests' already exists, skipping creation.\n";
        }
    }
    
    public function safeDown()
    {
        // Drop test database
        $this->execute("DROP DATABASE loans_test");
    }
}
