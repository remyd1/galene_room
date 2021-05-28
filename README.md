# galene_room

PHP Management code for [Galène](https://galene.org) rooms.

You will need a web server that have write permissions on a {sub,}group directory (see `inc/config.php`).

The {apache,nginx} (`www-data`) user must have write access to the {sub,}group directory.

To clean the group directory, just add a basic crontab; for instance:

```
@daily find /home/galene/groups/sub -mtime +30 -delete
```
