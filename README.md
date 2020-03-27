### Install:
```
composer require andrewdevelop/api-localization:dev-master
```
### Register service provider:
Open your bootstrap/app.php file and paste this
```
$app->register(Core\Localization\LocalizationServiceProvider::class)
```
### Configure:
Open your .env file and set default locale and the comma-separated list of available locales
```
APP_LOCALE=en
APP_LOCALES=en,ar,ru
```
