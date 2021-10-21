# galene_room

PHP Management code for [Galène](https://galene.org) rooms.

## Main purpose

When you have to admin a `Galène` server for a large audience, you need to create, delete rooms regularly.

Main idea is to let (advanced) users do this for you.

## Installation

For now, there is no specific release, only the `main` branch on github.

```bash
git clone https://github.com/remyd1/galene_room.git /var/www/html/room
```

This code is a PHP code for a web server; obviously, you will need :

  * `php`,
  * `php-fpm` or `mod_php` or `FastCGI`,
  * `php-curl` for [hcaptcha](#hcaptcha),
  * a web server, like `nginx` or `apache2`...
  * a [Galène](https://galene.org) server (easier if it is on the same server, otherwise you will need to share the {sub,}group directory for this web server.

## Configuration

Basic configuration is available in `inc/config.php`. Please adjust it to fit your needs.

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

Otherwise, if you do not need it, you can delete your `api` folder. However, for now, there is no other way to list rooms, delete a room or display room's details (obviously, you still can do this from your terminal on the server (...)).

You will find a nginx configuration example in the `utils` directory (using `php-fpm`, `Let's encrypt` and `Galène`). If you want 2 htpasswd files (one for room creation and another one for the api access), you can use `utils/nginx/galene_with2users`. If you do so, you will have to modify URLs for the api access; for instance, `https://galene.domain.tld/room/api/list.php` becomes `https://galene.domain.tld/api/list.php`.

### Hcaptcha

`php-curl` is required for [`hcaptcha`](https://www.hcaptcha.com/). To configure `hcaptcha`, please login on [their dashboard](https://dashboard.hcaptcha.com/settings) to create a _site key_ and a _secret key_.

You will need these values in `inc/hcaptcha.php`. Otherwise, you will have to disable `hcaptcha` (set it to `false`).

## Usage

### Room creation

For basic usage, you can create rooms by using the basic form (https://galene.domain.tld/room/create.php).

For the API, please use `curl`:

```bash
curl --user test_user:secret -H "Content-Type: application/json" -d '{"op": [{"username": "titi", "password":"toto"},{"username": "foo", "password":"bar"}],"presenter": [{}]}' https://galene.domain.tld/room/api/add.php
```

### Listing rooms

```bash
curl --user test_user:secret https://galene.domain.tld/room/api/list.php
```

### Display room details

For a **test** Galène `group`:

```bash
curl --user test_user:secret https://galene.domain.tld/room/api/read.php?filename=test.json
# or:
curl --user test_user:secret https://galene.domain.tld/room/api/read.php?groupname=test
```

## Delete a room

```bash
curl --user test_user:secret https://galene.domain.tld/room/api/delete.php?filename=test.json
# or:
curl --user test_user:secret https://galene.domain.tld/room/api/delete.php?groupname=test
```

