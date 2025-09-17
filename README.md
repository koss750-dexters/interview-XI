# Loan Application API
## Implementation timeline

- Requirements analysis: 10 min
- Architecture & Docker setup: 45 min  
- Implementation (TDD): 1 hour
- Testing & validation: 45 min
- Documentation: 36 min
- **Approximate total: 3 hours**

## Quick Start

```bash
docker-compose up -d
```

## Endpoints available at `http://localhost`

### Submit Loan Request
```
POST /requests
Content-Type: application/json

{
  "user_id": 1,
  "amount": 3000,
  "term": 30
}
```

**Response (201):**
```json
{
  "result": true,
  "id": 42
}
```

**Response (400):**
```json
{
  "result": false
}
```

### Process Requests
```
GET /processor?delay=5
```

**Response (200):**
```json
{
  "result": true
}
```

## Database

- **Host:** localhost:5432
- **Database:** loans
- **User:** user
- **Password:** password

## Business Rules

- Users can't have multiple approved loans
- 10% approval rate (random)
- Requests processed with configurable delay
- Concurrent processing supported

## Development

```bash
# Database migrations
docker-compose exec php ./yii migrate

# Run tests
docker-compose exec php vendor/bin/phpunit

# Logs
docker-compose logs -f
```

## Features Implemented

- ✅ **POST /requests** - Submit loan applications with validation
- ✅ **GET /processor** - Process pending requests with configurable delay  
- ✅ **Business Logic** - 10% random approval rate, one loan per user
- ✅ **Database** - PostgreSQL with proper constraints and indexes
- ✅ **Docker** - Full containerised setup with Nginx, PHP-FPM, PostgreSQL
- ✅ **Validation** - Input validation and error handling
- ✅ **Concurrency** - Database-level locking for thread safety
- ✅ **Testing** - PHPUnit setup with basic test framework

## API Examples

```bash
# Create loan request
curl -X POST http://localhost/requests \
  -H "Content-Type: application/json" \
  -d '{"user_id": 1, "amount": 3000, "term": 30}'

# Process requests  
curl "http://localhost/processor?delay=5"
```
