# galene_room

PHP Management code for [Galène](https://galene.org) rooms.

## Configuration

Basic configuration is available in `inc/config.php`. Please adjust it to fit your needs.

This code is a PHP code for a web server; obviously, you will need :

  * `php`,
  * `php-fpm` or `mod_php` or `FastCGI`,
  * `php-curl` for [hcaptcha](#hcaptcha),
  * a web server, like `nginx` or `apache2`...
  * a [Galène](https://galene.org) server (easier if it is on the same server, otherwise you will need to share the {sub,}group directory for this web server.

### Directory and web server

You will need a web server that have write permissions on a {sub,}group directory (see `inc/config.php`).

So, the {apache,nginx} user (eg.: `www-data`) must have write access to the {sub,}group directory.

Let's say we will create a subdirectory in the Galène group directory, for example /home/galene/groups/sub:

```bash
chown -R www-data /home/galene/groups/sub
```

To clean the group directory, just add a basic crontab; for instance:

```
@daily find /home/galene/groups/sub -mtime +30 -delete
```

To avoid access to the `api` which allow to create groups anonymously, please create an `.htpasswd` file:

```bash
apt-get install apache2-utils
htpasswd -c /etc/nginx/.htpasswd test_user
```

Otherwise, if you do not need, you can delete your `api` folder.

However, for now, there is no other way to list rooms, delete a room or display room's details (obviously, you still can do this from your terminal on the server (...)).

You will find a nginx configuration example in the `utils` directory.

### Hcaptcha

`php-curl` is required for [`hcaptcha`](https://www.hcaptcha.com/). To configure `hcaptcha`, please login on [their dashboard](https://dashboard.hcaptcha.com/settings) to create a _site key_ and a _secret key_.

You will need these values in `inc/hcaptcha.php`. Otherwise, you will have to disable `hcaptcha` (set it to `false`).

