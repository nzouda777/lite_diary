# Laravel Story API

A Laravel-based RESTful API for managing stories with Docker containerization. This application allows users to create, read, update, and delete stories, with public and private visibility options.

## Table of Contents

- [Project Overview](#project-overview)
- [Prerequisites](#prerequisites)
- [Docker Configuration](#docker-configuration)
- [Local Deployment](#local-deployment)
- [API Endpoints](#api-endpoints)
- [Authentication](#authentication)
- [Database Structure](#database-structure)

## Project Overview

This application provides a RESTful API for managing stories. Users can register, login, create stories, and manage their visibility (public or private). The application is containerized using Docker for easy deployment and development.

## Prerequisites

- Docker and Docker Compose
- Git (for cloning the repository)

## Docker Configuration

The application is containerized using Docker with the following services:

### Services

1. **PHP Application (app)**
   - Based on PHP 8.2-FPM
   - Contains the Laravel application code
   - Installs necessary PHP extensions for Laravel

2. **Nginx (nginx)**
   - Web server that handles HTTP requests
   - Configured to work with Laravel routing
   - Exposes port 8000 for accessing the application

3. **MySQL (db)**
   - MySQL 8.0 database server
   - Stores application data
   - Configured with initial database setup

### Configuration Files

- **Dockerfile**: Defines the PHP application container with necessary extensions and dependencies
- **docker-compose.yml**: Orchestrates the multi-container setup
- **docker/nginx/conf.d/app.conf**: Nginx configuration for the application
- **docker/mysql/init.sql**: Initial SQL commands for database setup

## Local Deployment

### Setup and Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/nzouda777/lite_diary.git
   cd docker-project
   ```

2. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

3. Update the .env file with the following database configuration:
   ```
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=laravel_db
   DB_USERNAME=root
   DB_PASSWORD=root
   ```

4. Start the Docker containers:
   ```bash
   docker-compose up -d
   ```

5. Install Composer dependencies:
   ```bash
   docker-compose exec app composer install
   ```

6. Generate application key:
   ```bash
   docker-compose exec app php artisan key:generate
   ```

7. Run database migrations:
   ```bash
   docker-compose exec app php artisan migrate
   ```

8. Access the application at:
   ```
   http://localhost:8000
   ```

### Useful Commands

- View logs:
  ```bash
  docker-compose logs -f
  ```

- Access the PHP container:
  ```bash
  docker-compose exec app bash
  ```

- Run Artisan commands:
  ```bash
  docker-compose exec app php artisan <command>
  ```

## API Endpoints

### Authentication

| Method | Endpoint | Description | Authentication Required |
|--------|----------|-------------|------------------------|
| POST | `/api/register` | Register a new user | No |
| POST | `/api/login` | Login and get access token | No |
| POST | `/api/logout` | Logout and invalidate token | Yes |

### Stories

| Method | Endpoint | Description | Authentication Required |
|--------|----------|-------------|------------------------|
| GET | `/api/stories` | Get all stories for authenticated user | Yes |
| GET | `/api/stories/public` | Get all public stories | No |
| GET | `/api/stories/{id}` | Get a specific story (if public or owned) | Conditional |
| POST | `/api/stories` | Create a new story | Yes |
| PUT | `/api/stories/{id}` | Update a story | Yes |
| DELETE | `/api/stories/{id}` | Delete a story | Yes |

## Authentication

The API uses Laravel Sanctum for token-based authentication.

### Registration

**Endpoint:** `POST /api/register`

**Request Body:**
```json
{
  "name": "User Name",
  "email": "user@example.com",
  "password": "password123",
  "pseudo": "username",
  "phone_number": "1234567890"
}
```

**Response:**
```json
{
  "access_token": "token_string",
  "token_type": "Bearer"
}
```

### Login

**Endpoint:** `POST /api/login`

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "access_token": "token_string",
  "token_type": "Bearer",
  "user": {
    "id": 1,
    "name": "User Name",
    "email": "user@example.com",
    "pseudo": "username",
    "phone_number": "1234567890"
  }
}
```

## Database Structure

### Users Table

Stores user information for authentication and story ownership.

### Stories Table

Stores story data with the following fields:
- `id`: Unique identifier
- `user_id`: Foreign key to users table
- `title`: Story title
- `content`: Story content
- `image`: Optional image path
- `status`: Story status
- `is_public`: Boolean flag for public/private visibility
- `slug`: URL-friendly identifier
- `created_at`: Creation timestamp
- `updated_at`: Last update timestamp
