
### /etc/hosts Additional

/etc/hosts

```
127.0.0.1 dev-home-standalone.dsign.gift
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