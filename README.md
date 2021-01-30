# E-Learning Backend

Elearning Backend is a backend system made using Laravel 8

## Installation
#### Install Dependency

Use the package manager [composer](https://getcomposer.org/) to install e-learning backend.

```bash
composer install
```

#### Copy .env file
```bash
php -r \"file_exists('.env') || copy('.env.example', '.env');\"
```

#### Generate Laravel Key
```bash
php artisan key:generate --ansi
```

#### Generate JWT Secret
```bash
php artisan jwt:secret
```

#### Migrate Database
```bash
php artisan migrate:fresh
```

#### Seed Database
```bash
php artisan db:seed
```

## Development Preview Mode

```bash
php artisan serve
```



## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
