# To-Do List API

This project is a simple To-Do List RESTful API built with Laravel, Docker, and MySQL. It allows users to register, log in, and manage their personal to-do tasks with authentication and pagination support.

## Features

- User registration and authentication (JWT or Passport)
- Create, read, update, and delete personal to-do items
- Paginated listing of to-dos (`GET /todos?page=1&limit=10`)
- Dockerized environment with Nginx, PHP-FPM, and MySQL

## Getting Started

### Prerequisites

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

### Setup

1. **Clone the repository:**
   ```sh
   git clone https://github.com/yourusername/to-do-list.git
   cd to-do-list
   ```

2. **Start the containers:**
   ```sh
   docker-compose up --build
   ```

3. **Install dependencies and set up Laravel:**
   ```sh
   docker-compose exec app composer install
   docker-compose exec app cp .env.example .env
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate
   ```

4. **Access the API:**
   The API will be available at [http://localhost:8000](http://localhost:8000).

## API Endpoints

### Authentication

- `POST /api/register` — Register a new user
- `POST /api/login` — Log in and receive a token
- `POST /api/logout` — Log out (requires authentication)
- `GET /api/me` — Get current user info (requires authentication)

### To-Do Management

- `POST /api/todos` — Create a new to-do (requires authentication)
- `GET /api/todos` — Get all to-dos for the authenticated user (requires authentication)
- `GET /api/todos?page=1&limit=10` — Get paginated to-dos (requires authentication)
- `GET /api/todos/{id}` — Get a specific to-do (requires authentication)
- `PUT /api/todos/{id}` — Update a to-do (requires authentication)
- `DELETE /api/todos/{id}` — Delete a to-do (requires authentication)

### Example: Paginated To-Do List

Request:
```
GET /api/todos?page=1&limit=10
Authorization: Bearer YOUR_TOKEN
```

Response:
```json
{
  "data": [
    {
      "id": 1,
      "title": "Buy groceries",
      "description": "Buy milk, eggs, bread",
      "completed": false
    }
  ],
  "page": 1,
  "limit": 10,
  "total": 1
}
```

## Development

- **Database:** MySQL (see `docker-compose.yml` for credentials)
- **Web server:** Nginx (configured in `docker/nginx/default.conf`)
- **PHP:** 8.2 FPM

## License

This project is open-sourced software licensed under