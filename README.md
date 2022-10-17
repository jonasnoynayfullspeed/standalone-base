
### /etc/hosts Additional

/etc/hosts

```
127.0.0.1 dev-standalone-base.local.host
```

### Create SSL certificate

```
cd docker/file/ssl
mkcert "*.local.host"
```

### Start the docker container

```
docker-compose up -d
```