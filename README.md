# CV Generator news (articles) service

Service for storing CV Generator news (articles).

## Main Project
- [CV Generator API](https://github.com/m1n64/cv-generator-api)

## Stack
- Symfony 7.2
- PHP 8.3
- PostgreSQL 14.2
- Redis

## Initialization
0. Create docker network
```
docker network create cv-generator-network
```

1. Update and install Composer packages
```
make bash
```

```
composer update
```

2Build and up project with Docker Compose

```
docker-compose up -d --build
```

3. Open `http://localhost:8000` in your browser, you should see the Symfony's welcome page.

## Using

### Using Docker Compose

Build and up:

```
docker-compose up -d --build
```

Up only:

```
docker-compose up -d
```

Down:

```
docker-compose down
```

Rebuild and up:

```
docker-compose down -v --remove-orphans
docker-compose rm -vsf
docker-compose up -d --build
```

### Using PostgreSQL

All .sql files inside `docker/postgres/conf` will be executed on container build. Currently, there is `docker/postgres/conf/create_test_table.sql` file, creating `test_table` with 3 records for testing purposes. It can be safely deleted.

Postgres database name, user and password defined in `.env` file.

Connect to database with default login:

```
docker-compose exec postgres psql -U root cv_news
```

### Using PHP

PHP config located at `docker/php/conf/php.ini`. You might want to change `date.timezone` value in `php.ini`. Default value is `Europe/Helsinki` (GMT+3).

Execute command `php -v` in the `php` container:

```
docker-compose exec php php -v
```

*There is OPCache commented out settings in `php.ini` as well as loading line in `Dockerfile` if you need it, by any means. Not recommended for development environment.*

### Using PHPUnit

There is two test for testing Docker environment:

1. PHPUnit self test
2. PostgreSQL connection test

Run the tests:

```
docker-compose exec php vendor/bin/phpunit ./tests
```

Successfully passed tests output:

```
OK (2 tests, 2 assertions)
```

### Using Xdebug

XDebug config located at `docker/php/conf/xdebug.ini`.

To enable Xdebug at the project build, set `INSTALL_XDEBUG` variable to `true` in `.env` file.

### Using Makefile

To execute Makefile command use `make <command>` from project's folder

List of commands:

| Command | Description |
| ----------- | ----------- |
| up | Up containers |
| down | Down containers |
| build | Build/rebuild continers |
| test | Run PHPUnit tests |
| bash | Use bash in `php` container as `www-data` |
| bash_root | Use bash in `php` container as `root` |

***
### WIP