# The plugin handling file cache in find for CakePHP

## What is it for?
It is model behavior for static models.
If you want to use file cache for static model, this plugin is easy to use.

## Installation
You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).
The recommended way to install composer packages is:
```
composer require m-shimao/find-in-file-cache
```

Also don't forget to load the plugin in your bootstrap:
```php
Plugin::load('FindInFileCache');
// or
Plugin::loadAll();
```

## Usage
### Table
```
<?php
class CategoriesTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('FindInFileCache.FindInFileCache');
    }
}
```
### Config
Default Setting is below.
```
'className' => 'File',↲
'prefix' => 'myapp_cake_static_record_',↲
'path' => CACHE . 'static_records/',↲
'duration' => '+15 minutes',↲
'mask' => 0666,↲
```
If you use original setting, like below.
```
<?php
return [
...
    'Cache' => [
        ...
        'find-in-file' => [
            'className' => 'File',↲
            <type your setting>
        ],
        ...
    ],
...
];
```
