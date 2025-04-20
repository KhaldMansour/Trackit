# TrackIt - Laravel Backend Project

TrackIt is a Laravel-based backend system built to manage application tracking features efficiently.

## 📦 Requirements

- PHP 8.3+
- Composer
- MySQL 8.0+

## 🚀 Getting Started

1. **Clone the Repository**

``` bash
git clone git@github.com:KhaldMansour/Trackit.git
```

2. **Navigate to the Project Directory**

```bash
cd trackit
```

3. **Install PHP Dependencies**

```bash
composer install
```

4. **Copy and Configure the Environment File**

```
cp .env.example .env
```

Edit the `.env` file to match your database and application configuration:

Example:

`APP_NAME=TrackIt`  
`APP_URL=http://localhost`  
`DB_HOST=127.0.0.1`  
`DB_PORT=3306`  
`DB_DATABASE=trackit`  
`DB_USERNAME=root`  
`DB_PASSWORD=root`

5. **Generate Application Key**

```bash
php artisan key:generate
```

6. **Generate JWT Secret**

```bash
php artisan jwt:secret
```

7. **Run Migrations and Seeders**

```bash
php artisan migrate --force --seed
```

## 📄 API Documentation (Swagger)

Swagger documentation is available for the API endpoints.

To access the Swagger UI, start the Laravel server:

```bash
php artisan serve
```

Then open this URL in your browser:

`http://localhost:8000/api/documentation`

Make sure your routes and Swagger annotations are set up correctly using the appropriate Laravel Swagger package (e.g., `DarkaOnLine/L5-Swagger`).


## 🔐 Authentication

This project uses **JWT** for API authentication.

To test authentication endpoints:

- Register/Login a user
- Use the returned token as a `Bearer` token in your requests

## 🎯 Common Artisan Commands

- Clear cache: `php artisan config:clear && php artisan cache:clear`
- Run tinker: `php artisan tinker`
- Serve the app locally: `php artisan serve`

## 🙌 Contribution

Contributions are welcome!
