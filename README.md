# Test29

REST API автомобилей.

## Доступ к документации Swagger

Документация доступна по корневому пути '/'

## Возможности

-   Регистрация и авторизация пользователей (JWT токен)
    'api/login';
    'api/register
-   Просмотр автомобилей
    'api/cars';
-   Просмотр брендов
    'api/brands';
-   Просмотр моделей авто
    'api/car-models';

## Технологии

-   **Backend**: Laravel 12
-   **База данных**: MySQL
-   **Авторизация**: JWT
-   **Тесты**: PHPUnit
-   **Документация**: Swagger

## Установка

1. Клонировать репозиторий:

```bash
git clone https://github.com/ManasArs13/test29.git && cd test29
```

2. Установите зависимости:

```bash
composer install && npm install && npm run build
```

3. Настройте:

```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

4. Запустить миграции:

Для удобства тестирования созданы тестовые данные, использую Factories и Seeders

```bash
php artisan migrate --seed
```

## Структура базы данных

-   users - зарегистрированные пользователи
-   cars - авто
-   car-models - модели авто
-   brands - марки авто

## Тестирование

Проект включает комплексные тесты для:
- Brand - 7 тестов;
- CarModel - 7 тестов;
- Car - 8 тестов

Запуск тестирования:

```bash
php artisan test
```




