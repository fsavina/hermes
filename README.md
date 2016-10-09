# Hermes

> _"Hermes is considered a god of transitions and boundaries. He is described as quick and cunning, moving freely
between the worlds of the mortal and divine." (Wikipedia)_

***

- [Gettings started](#getting-started)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Commands](#commands)
    - [hermes:setup](#setup-command)
    - [hermes:deploy](#deploy-command)
    - [hermes:task](#task-command)
- [Extending Hermes](#extending-hermes)
    - [Custom Tasks](#custom-tasks)
    - [Custom Routines](#custom-routines)

***

<a name="getting-started"></a>
## Getting Started
An automatic PHP deploy system via Git on SSH.

<a name="requirements"></a>
## Requirements
In order to work, Hermes needs **Git** and **SSH** available in your local environment, as well as all the **SSH keys**
that are required to access the remote servers.

<a name="installation"></a>
## Installation
First of all, add this package to your project via Composer. Require the package in the `dev` section of your `composer.json`.

```json
    "require-dev": {
        "fsavina/hermes" : "1.0.*"
    }
```

and update the Composer dependencies from your terminal:
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

and complete the installation publishing the `laravelcollective/remote` package default configuration file:
```shell
    php artisan vendor:publish --provider="Collective\Remote\RemoteServiceProvider"
```


<a name="configuration"></a>
## Configuration
Since this package uses the `laravelcollective/remote` package to operate on the remote servers, you need
to extend your remotes configurations in the `config/remote.php` configuration file.

In order to automatically deploy your code to a remote server you need to set the following values:
```php
    'stage'            => [
        ...

        'remote'     => 'stage',
        'root'       => '/path/to/project/root',
        'repository' => '/path/to/remote/repository.git',
        'tasks'      => ['composer:install', 'cache:clear'],
        'commands'   => [
            'mkdir /some/dir/foo/bar'
        ]
    ],
```

The `remote` value is the name of a local Git remote pointing to the remote `repository` on the target server. It can be
either an existing remote or it can be created via the [`setup command`](#setup-command).

The `repository` value is the complete path to the gateway Git repository on the remote server.

The `root` value is complete path to the target folder for your project on the remote server.

The `tasks` array is the list of built-in or custom tasks that you want to execute every time that the code si transfered
to the remote server (eg. install new packages, clean up the cache).

The `commands` array is optional and can contain a list of shell commands to be executed after both the code transfering
and the tasks execution.

> For the basic remote configuration to access the remote server via SSH
see the [laravelcollective/remote](https://laravelcollective.com/docs/5.3/ssh#configuration) website.


<a name="commands"></a>
## Available Commands

<a name="setup-command"></a>
### hermes:setup

<a name="deploy-command"></a>
### hermes:deploy

<a name="task-command"></a>
### hermes:task

<a name="extending-hermes"></a>
## Extending Hermes

<a name="custom-tasks"></a>
### Custom Tasks

<a name="custom-routines"></a>
### Custom Routines