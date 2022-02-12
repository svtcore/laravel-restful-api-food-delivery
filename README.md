# Laravel API food delivery

Personal project which provide API for food delivery service based on Laravel Framework (could be used like API for mobile app client)

List of API Docs you can see [HERE](https://github.com/svtcore/laravel-restful-api-food-delivery/blob/main/API-DOCS.md)

## Installation
1. Clone repository

3. Install composer
```
composer install
```
3. Rename file **.env.example** to **.env**

4. Generate app key
```
php artisan key:generate
```
5. Open **.env** and fill database data
6. Clear cache
```
php artisan cache:clear
```
7. Make migration with seeding
```
php artisan migrate --seed
```

## Run
```
php artisan serve
```
## Contributing
Pull requests are welcome.

## License
[MIT](https://github.com/svtcore/laravel-restful-api-food-delivery/blob/main/LICENSE)

