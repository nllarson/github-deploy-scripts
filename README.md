# github-deploy-scripts

Use a GitHub webhook event to deploy / update your code on your webserver.

## Installation
Copy `github.php` and `github.config.php` to the base of your checked out code on your server.  These scripts need to be accessible by an HTTP POST.

## Setup
Create a webhook on your GitHub repo.  (**Settings** -> **Webhooks & Services**).

![Webhook Example](nllarson.github.io/webhood-setup.png)

Make sure your webhook's **Content Type** setting is `application/x-www-form-urlencoded`.  The default of `application/json` is not supported with this script.

## Configuration

`github.config.php` contains all your configuration for the scripts to work.

### `limit_branch`
  * type: `boolean`
  * default: `false`

Webhooks events are sent for every push to your repository.  (To any branch / tag)  There are times where you will only want this script to run only for pushes to specific branches.

**Note:** if set to *true*, you will need to also set the `valid_branches`, so that the script knows what events to execute on.

### `valid_branches`
  * type: `array`
  * default: `[]`

```php
$valid_branches = array("ref/heads/master");
// or
$valid_branches = array("ref/heads/master","ref/heads/prod");
```

This array should contain the branch names (as specified by the Webhook Payload `$_POST[payload][ref]`) that are valid for the script to execute.  This array is only checked when the `limit_branch` flag is set to *true*.

### `enabled`
  * type: `boolean`
  * default: `true`

Kill switch for the main logic of the script.  If set to false, nothing will ever get deployed, but you will get an email notification that the script was called, just disabled.

**Note:** The `limit_branch` check is run first, there could be scenarios where you have the script disabled and you will not receive the notification.  

### `git_update`
  * type: `boolean`
  * default: `true`

Flag to do the actual `git pull` command.  Shut off for trial runs / testing.

### `to_email`
  * type: `string`
  * **REQUIRED**

```php
$to_email = "someuser@somedomain.com";
```

The email to send all notifications to.

### `from_email`
  * type: `string`
  * **REQUIRED**

```php
$from_email = "admin@yourdomain.com";
```

The email that is sending the notification.  For some hosting providers (Dreamhost), this email *MUST* be from the domain that is hosting the site.

### `site_name`
  * type: `string`
  * **REQUIRED**

```php
$site_name = "GitHub Deploy Scripts";
```

The name of your site.  Used in the notifications.

### `limit_users`
  * type: `boolean`
  * default : `false`

Flag to set if you are wanting to whitelist which users can actually deploy the code.

**Note:** If `true`, you must specify the users in the `valid_users` option.

### `valid_users`
  * type: `array`
  * deafault `[]`

Array of valid github usernames that are allowed to deploy from push.
