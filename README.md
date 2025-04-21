# 🎧 Podcast Platform API

A RESTful API built with **Laravel 12**, **PHP 8.x**, **MySQL**, **Docker**, and **Swagger** for a Podcast Platform.

---

## 🚀 Features

- User Registration & Authentication (Sanctum)
- Podcast & Episode Endpoints
- Categories Management
- Password Reset with Token Expiry
- Swagger (OpenAPI) API Documentation
- Dockerized Environment

---

## 🛠 Tech Stack

- Laravel 12
- PHP 8.x
- Sanctum for API Authentication
- MySQL
- Swagger for API Docs
- Docker + Docker Compose

---

## 📦 Installation

```bash
# Clone the repo
git https://github.com/oseun123/podcastApp.git
cd podcastApi

# Copy environment file
cp .env.example .env

# Build and start containers
docker-compose up -d --build

# Install dependencies
docker-compose exec app composer install

# Generate application key
docker-compose exec app php artisan key:generate

# Run migrations
docker-compose exec app php artisan migrate:fresh --seed
