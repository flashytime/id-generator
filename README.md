# ID Generator
ID Generator based on Apc and MySQL which can be used to generate primary key for sharding mysql tables.

## Installation
- Run the composer require command from your terminal:

```bash
composer require flashytime/id-generator
```

- Create a mysql table named 'id_generator' with the `id_generator.sql`:

```sql
CREATE TABLE `id_generator` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '名字',
  `current_id` bigint(20) unsigned NOT NULL COMMENT '当前最大ID',
  `step` int(11) unsigned NOT NULL COMMENT '步长',
  `length` int(11) unsigned NOT NULL COMMENT '缓存步长',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ID生成器';
``` 

- Copy the `id-generator.php` file from the config directory to `config/id-generator.php`

## Usage

```php
$idGenerator = new \Flashytime\ApcIdGenerator\IdGenerator($config);
$idGenerator->setTable('id_generator');
$id = $idGenerator->getId('test_name');
```

## Laravel and Lumen

#### Laravel 5

Add a ServiceProvider to your providers array in `config/app.php`:

```php
'providers' => [

	Flashytime\IdGenerator\Provider\LaravelIdGeneratorServiceProvider::class,

]
```

Finally, publish the configuration files via `php artisan vendor:publish`.

#### Lumen

For `Lumen` add the following in your bootstrap/app.php
```php
$app->register(Flashytime\IdGenerator\Provider\LumenIdGeneratorServiceProvider::class);
```

Copy the `id-generator.php` file from the config directory to `config/id-generator.php`

And also add the following to bootstrap/app.php
```php
$app->configure('id-generator');
```

#### Usage

```php
app('id-generator')->setTable('id_generator');
$id = app('id-generator')->getId('test_name');
```

## License
MIT

