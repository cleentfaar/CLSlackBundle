# Installation

## Step 1) Get the bundle

First you need to get a hold of this bundle. There are two ways to do this:

### Method a) Using composer

Add the following to your ``composer.json`` (see http://getcomposer.org/)

    "require" :  {
        "cleentfaar/slack-bundle": "~0.10"
    }


### Method b) Using submodules

Run the following commands to bring in the needed libraries as submodules.

```bash
git submodule add https://github.com/cleentfaar/CLSlackBundle.git vendor/bundles/CL/Bundle/SlackBundle
```


## Step 2) Register the namespaces

If you installed the bundle by composer, use the created autoload.php  (jump to step 3).
Otherwise, add the following two namespace entries to the `registerNamespaces` call in your autoloader:

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

To start using the bundle, register it in your Kernel (note the required `JMSSerializerBundle`).

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new CL\Bundle\SlackBundle\CLSlackBundle(),
        // ...
    );
}
```

## Step 4) Configure the default API token to use (optional)

If you don't have an API token yet, follow this link: [https://api.slack.com/web](https://api.slack.com/web).
It takes you to the Slack API site which (if you are logged in, then scroll down) lets you
generate an API token for your account.

The bundle tries to make sending payloads to Slack a little easier by letting you
define the API token to use beforehand.

**NOTE:** Setting the token beforehand is not required; you can still choose to leave the
configuration empty and pass the API token of your choice when sending a payload: `$apiClient->send($payload, 'your-token-here')`.

Here is an example:
```yaml
# app/config/config.yml
cl_slack:
    api_token: 1234 # replace with your own (see: https://api.slack.com/tokens)
```

This is all you need to start working with this bundle. If you would like to see a complete reference of
all configuration options, check out the [configuration](https://github.com/cleentfaar/CLSlackBundle/blob/master/Resources/doc/configuration.md) chapter.


# Ready?

Let's start interacting with the Slack API! Check out the [usage documentation](https://github.com/cleentfaar/CLSlackBundle/blob/master/Resources/doc/usage.md)!
