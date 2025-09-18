-- Create test database
CREATE DATABASE loans_test;

-- Create loan requests table in main database
CREATE TABLE loan_requests (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    amount INTEGER NOT NULL CHECK (amount > 0),
    term INTEGER NOT NULL CHECK (term > 0),
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'approved', 'declined')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    processed_at TIMESTAMP NULL
);

-- Indexes for performance
CREATE INDEX idx_user_status ON loan_requests(user_id, status);
CREATE INDEX idx_status_created ON loan_requests(status, created_at);
