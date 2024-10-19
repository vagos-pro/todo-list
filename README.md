<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Зависимости

* PHP 8.3
* Laravel 9
* Mysql 8.0
* [Docker](https://www.docker.com/get-started) & [Laravel Sail](https://laravel.com/docs/9.x/sail#main-content)

<hr>

## Начало работы (первый запуск)

1. Клонирование `git clone git@github.com:vagos-pro/todo-list.git`
2. Скопировать `.env.example` под именем `.env` и установить значения локального окружения
3. Запуск контейнеров: `make start`
4. Установка зависимостей composer `make composer-install`
5. Запуск миграций `./vendor/bin/sail artisan migrate`
6. Запуск тестов `./vendor/bin/sail artisan test`

## API Documentation Swagger

Path: `http://localhost/api/documentation/`

Generate docs `./vendor/bin/sail artisan l5-swagger:generate`

For auto-generate docs: `L5_SWAGGER_GENERATE_ALWAYS=true` in `.env`


## Xdebug config

Path: `docker/php/config/xdebug.ini`

```angular2html
zend_extension=/usr/lib/php/20230831/xdebug.so
xdebug.mode=debug
xdebug.start_with_request=yes
xdebug.client_port=9003
xdebug.idekey=PHPSTORM
xdebug.client_host=
```
For Macos & Windows:
```
xdebug.client_host=host.docker.internal
```
For Linux

```
xdebug.client_host=172.17.0.1
```
