# MyOPAC Extension
## What is this?


## Installtion
Clone MyOPAC Extension to your server.

```
git clone https://github.com/karadaharu/myopac-extension.git
```

Then, install dependencies by [composer](https://getcomposer.org/).

```
php composer.phar install
```

Write your ID and password to ```users.json```.

Access to the URL of your server from your browser to get the authentication of your Google acount.

If you cannnot use ```crontab``` on your server (i.e. a cheap rental server), set a cron job mannually to run ```check.php```.
