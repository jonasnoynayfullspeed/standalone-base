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

### Before git push
- update ide helper
- code format
- check php syntax error
- php artisan optimize
```
./vendor/bin/phing build
```