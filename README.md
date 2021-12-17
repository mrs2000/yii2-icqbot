PHP Library for ICQ bot API
=================

[![Latest Stable Version](https://img.shields.io/packagist/v/mrssoft/yii2-icqbot.svg)](https://packagist.org/packages/mrssoft/yii2-icqbot)
![PHP](https://img.shields.io/packagist/php-v/mrssoft/yii2-icqbot.svg)
![Total Downloads](https://img.shields.io/packagist/dt/mrssoft/yii2-icqbot.svg)

ICQ Bot API
---
https://icq.com/botapi/

Installation
---

The preferred way to install this extension is through 
[composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist mrssoft/icqbotapi "*"
```

or add

```
"mrssoft/icqbotapi": "*"
```

to the require section of your `composer.json` file.

Usage
---

```php
$bot = new \mrssoft\icqbotapi\IcqBot();
$bot->token = 'token';

$response = $bot->self();

$bot->sendText('@nick', 'Message');

$bot->sendText('@nick', 'Message', [
    [
        'text' => 'Button', 
        'callbackData' => 'my-data', 
        'style' => 'primary'
    ]
]);
```

```php
$bot = new \mrssoft\icqbotapi\IcqBot();
$bot->token = 'token';
$bot->mutex => FileMutex::class;

$events = $bot->pollEvents();

foreach ($events as $event) {
    if ($event instanceof IcqEventCallbackQuery) {
        $event->answer([
            'text' => 'callbackData: ' . $event->callbackData,
            'showAlert' => true,
            //'url' => 'https://ya.ru/',
        ]);
    }
}
```