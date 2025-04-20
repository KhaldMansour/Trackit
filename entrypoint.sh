#!/bin/bash

composer install
php artisan migrate --seed
php artisan key:generate
php artisan jwt:secret