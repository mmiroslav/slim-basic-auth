# Basic Auth Middleware for Slim

This middleware implements HTTP Basic Authentication for Slim Framework.

## Install

You can install the middleware using composer.

```javascript
{
    "require": {
        "mmiroslav/slim-basic-auth": "dev-master",
    }
}
```

## Usage

Configuration options are passed as an array. Only mandatory parameter is  `users`. This is an array where you pass one or more `"username" => "password"` combinations. Username is the key and password is the value.

```php
$app = new \Slim\Slim();

$app->add(new \Slim\Middleware\HttpBasicAuth(array(
    "users" => array(
        "root" => "t00r",
        "user" => "passw0rd"
    )
)));
```

With optional `path` parameter can authenticate only given part of your website. You can also change the displayed `realm` using the parameter with same name.
In `responseContent` place text for response body.

```php
$app = new \Slim\Slim();

$app->add(new \Slim\Middleware\HttpBasicAuth(array(
    "path" => "/admin",
    "realm" => "Protected",
    "responseContent" => " 401 Unauthorized",
    "users" => array(
        "root" => "t00r",
        "user" => "passw0rd"
    )
)));
```

