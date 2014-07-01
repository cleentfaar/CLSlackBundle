# Installation

## Step 1) Get the bundle and the library

First, grab the CLSlackBundle. There are two ways to do this:


### Method a) Using composer

Add the following to your ``composer.json`` (see http://getcomposer.org/)

    "require" :  {
        // ...
        "cleentfaar/slack-bundle": "1.0.*@dev"
    }


### Method b) Using submodules

Run the following commands to bring in the needed libraries as submodules.

```bash
git submodule add https://github.com/cleentfaar/CLSlackBundle.git vendor/bundles/CL/Bundle/SlackBundle
```

## Step 2) Register the namespaces

If you installed the bundle by composer, use the created autoload.php  (jump to step 3).
Add the following two namespace entries to the `registerNamespaces` call in your autoloader:

``` php
<?php
// app/autoload.php
$loader->registerNamespaces(array(
    // ...
    'CL\Bundle' => __DIR__.'/../vendor/bundles',
    // ...
));
```

## Step 3) Register the bundle

To start using the bundle, register it in your Kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new CL\Bundle\SlackBundle\CLSlackBundle(),
    );
    // ...
}
```

## Step 4) Configure the bundle

The bundle requires you to define some initial configuration, which is listed below.

```yaml
# app/config/config.yml
cl_slack:
    team: MyTeam # replace with the name of your team in Slack
    api_token: xoxp-1234567890-1234567890-1234567890-1a1234 # replace with your own (see: https://api.slack.com/tokens)
    outgoing_webhook_tokens:
        my_trigger_word: AbCde1A1ABcdEfGHIjk123Ab # optional, only if you want to respond to a outgoing webhook
```

**Note:** You do not need to have the outgoing_webhook_tokens entry if you do not wish to respond to any Outgoing Webhooks.


# Ready?

Check out the [usage documenatation](usage.md)!
