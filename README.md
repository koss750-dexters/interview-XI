# Loan Application API
## Implementation timeline

- Requirements analysis: 10 min
- Architecture & Docker setup: 45 min  
- Implementation (TDD): 1 hour
- Testing & validation: 45 min
- Debugging: 55 min
- **Approximate total: 3 hours**

## Quick Start

```bash
docker-compose up -d
```

## Endpoints available for `http://localhost`

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

The application automatically sets up the database and runs tests during container startup.

**Database Setup:**
- Main database (`loans`) is created via `init.sql` 
- Test database (`loans_test`) is created via `init.sql`
- Table structures are managed via Yii2 migrations
- All migrations run automatically during container startup

```bash
# Run tests (optional - already run during startup)
docker-compose exec php vendor/bin/phpunit

# Manual database migrations (optional - already run during startup)
docker-compose exec php ./yii migrate

# Logs
docker-compose logs -f
```


## API usage with Curl examples

```bash
# Create loan request
curl -X POST http://localhost/requests \
  -H "Content-Type: application/json" \
  -d '{"user_id": 1, "amount": 3000, "term": 30}'

# Process requests  
curl "http://localhost/processor?delay=5"
```
