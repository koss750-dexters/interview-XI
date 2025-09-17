# Repository Setup Instructions

## Creating the GitHub Repository

### 1. Create Repository on GitHub
```bash
# Go to GitHub.com and create a new repository:
# - Repository name: interview-xi
# - Description: Loan Application API - Yii2, PostgreSQL, Docker
# - Visibility: Public
# - Initialize with: None (we'll push existing code)
```

### 2. Initialize Local Git Repository
```bash
# Navigate to project directory
cd /Users/koss/InterviewTest_11

# Initialize git repository
git init

# Add all files
git add .

# Create initial commit
git commit -m "Initial commit: Loan Application API

- Yii2 framework with PostgreSQL database
- Docker containerisation with Nginx, PHP-FPM, PostgreSQL
- REST API endpoints: POST /requests, GET /processor
- Business logic: 10% approval rate, one loan per user
- Input validation and error handling
- Test-driven development with PHPUnit
- Production-ready with proper constraints and indexes"

# Add remote origin
git remote add origin https://github.com/YOUR_USERNAME/interview-xi.git

# Push to main branch
git branch -M main
git push -u origin main
```

### 3. Verify Repository
```bash
# Check remote
git remote -v

# Check status
git status

# View commit history
git log --oneline
```

## Project Structure
```
interview-xi/
├── README.md                 # Project documentation
├── SETUP.md                 # This setup guide
├── docker-compose.yml       # Docker orchestration
├── composer.json            # PHP dependencies
├── phpunit.xml             # Test configuration
├── .gitignore              # Git ignore rules
├── web/                    # Web entry point
├── config/                 # Yii2 configuration
├── controllers/            # API controllers
├── models/                 # Data models
├── migrations/             # Database migrations
├── tests/                  # Unit tests
└── docker/                 # Docker configurations
    ├── nginx/
    ├── php/
    └── postgres/
```

## Quick Start for Users
```bash
# Clone repository
git clone https://github.com/YOUR_USERNAME/interview-xi.git
cd interview-xi

# Start application
docker-compose up -d

# Test API
curl -X POST http://localhost/requests \
  -H "Content-Type: application/json" \
  -d '{"user_id": 1, "amount": 3000, "term": 30}'
```

## Development Workflow
```bash
# Make changes
# Test locally
docker-compose up -d
curl http://localhost/processor?delay=1

# Commit changes
git add .
git commit -m "Description of changes"
git push origin main
```
