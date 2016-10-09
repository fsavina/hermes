# Hermes

> _"Hermes is considered a god of transitions and boundaries. He is described as quick and cunning, moving freely
between the worlds of the mortal and divine." (Wikipedia)_

## Getting Started
An automatic PHP deploy system via Git on SSH.

## Requirements

## Installation
First of all, add this package to your project via Composer. Require the package in the `dev` section of your `composer.json`.

```json
    "require-dev": {
        "fsavina/hermes" : "1.0.*"
    }
```

And update the Composer dependencies from your terminal:
```shell
    composer install
```

Add the **Service Provider** to the list of providers in your `config/app.php`:
```php
    'providers' => [
        // ...
        Hermes\HermesServiceProvider::class,
        // ...
      ],
```

And complete the installation publishing the `Remote` default configuration file:
```shell
    php artisan vendor:publish --provider="Collective\Remote\RemoteServiceProvider"
```


## Configuration

## Available Commands

### hermes:setup

### hermes:deploy

### hermes:task

## Extending Hermes

### Custom Tasks

### Custom RoutineDrivers