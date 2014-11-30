# Installation

## Step 1) Get the bundle

First you need to get a hold of this bundle. There are two ways to do this:

### Method a) Using composer

Add the following to your ``composer.json`` (see http://getcomposer.org/)

    "require" :  {
        "cleentfaar/slack-bundle": "~0.10"
    }

**NOTE:** There is no stable version yet because Slack's API is not yet stable.

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
    'CL\Bundle\SlackBundle' => __DIR__.'/../vendor/bundles/cleentfaar/slack-bundle',
    // ...
));
```


## Step 3) Register the bundle

To start using the bundle, register it in your Kernel.

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

The bundle requires you to define a small piece of configuration before you start, here is an example:
```yaml
# app/config/config.yml
cl_slack:
    api_token: xoxp-1234567890-1234567890-1234567890-1a1234 # replace with your own (see: https://api.slack.com/tokens)
```


# Ready?

Let's start interacting with the Slack API! Check out the [usage documentation](usage.md)!
