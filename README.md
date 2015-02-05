Constant behavior for Yii2
==========================
Easy way to extract constants from yii2 model

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist chungachguk/yii2-constant-trait "*"
```

or add

```
"chungachguk/yii2-constant-trait": "*"
```

to the require section of your `composer.json` file.


Usage
-----

```php
return self::getConstants([
    'prefix' => 'TYPE_',
    'keyPattern' => '{value}',
    'valuePattern' => function($key, $value) {
        return Yii::t('app', ucfirst($value) . ' type');
    },
]);
```
