## Hashrate calculator

### Setup

Standard laravel app installation

Used sqlite to save time on database configuration. So, it is needed to be created.
``` bash
touch database/database.sqlite 
```

There is a seeder, to test.
``` bash
php artisan db:seed 
```

If proxy needed, edit .env file.
Set USE_PROXY to 1 to enable proxy, set 0 to turn off.
``` dotenv
USE_PROXY=1
```

And edit proxy configuration.
``` dotenv
PROXY="TYPE://IP:PORT"
```
Where `TYPE` is type of proxy, ex: socks5, socks4, http, https. 
`IP`, ip address of a proxy, ex: 8.8.8.8. 
And `PORT`, port of a proxy, ex: 1080

Example:
``` dotenv
PROXY="socks5://8.8.8.8:1080"
```


If proxy with authentication, use example below
``` dotenv
PROXY="TYPE://USERNAME:PASSWORD@IP:PORT"
```
Where `USERNAME` is username of a proxy. And `PASSWORD` is password.

Example:
``` dotenv
PROXY="socks5://ivan:qwerty123@8.8.8.8:1080"
```

### Setup cron job

open crontab
``` bash
crontab -e 
```
add following command to the end
``` cron
* * * * * cd /project-path && php artisan schedule:run >> /dev/null 2>&1
```
