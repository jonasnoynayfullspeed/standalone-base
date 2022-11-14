## Overview
Standalone-base is a project for compiling multiple modules in one repository. We're using [Laravel Modules by nWidart](https://github.com/nWidart/laravel-modules) for component modularizations.

### /etc/hosts Additional

/etc/hosts

```
127.0.0.1 dev-standalone-base.local.host
```

### Create SSL certificate

```
cd docker/ssl
mkcert "*.local.host"
```

### Start the docker container

```
docker-compose up -d
```

### Git Hooks
pre-commit executions: 
- update ide helper
- code format
- check php syntax error
- php artisan optimize
- unit test

### Notes
- `php 8.1` must be installed in host machine. \
`brew install php@8.1`
- run `npm install`
- prepare `.env.testing` file
