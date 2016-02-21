# MyOPAC Extension
## What is this?
This is an simple application to make MyOPAC, which is the system for the Univ. Tokyo Library convenient.

MyOPAC Extension does the following things automatically:

* put the deadline for returning books on your Google Calendar
* extend the deadline when the deadline comes

## Installation
Clone MyOPAC Extension to your server.

```
git clone https://github.com/karadaharu/myopac-extension.git
```

Then, install dependencies by [composer](https://getcomposer.org/).

```
php composer.phar install
```

Create Google Developer API OAuth 2.0 cliend ID and save as ```data/client_secret.json ```. The reference is [here](https://developers.google.com/identity/protocols/OAuth2)

Write your ID and password to ```data/users.json```.

Access to the URL of your server from your browser to get the authentication of your Google acount.

If you cannnot use ```crontab``` on your server (i.e. a cheap rental server), set a cron job mannually to run ```check.php```.
