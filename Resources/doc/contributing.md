# Contributing

**IMPORTANT:** Although I am happy to include your PRs, contributions may get delayed from merging until the official
Slack API has reached a stable version. I am anticipating this to happen very soon though, and will then change the
version of this package to 1.0 (stable) to reflect that.

## Coding standards

Your PRs must adhere to the [Symfony2 Coding Standards](http://symfony.com/doc/current/contributing/code/standards.html).
The easiest way to apply to these conventions is to install [PHP_CodeSniffer](http://pear.php.net/package/PHP_CodeSniffer)
and the [Opensky Symfony2 Coding Standard](https://github.com/opensky/Symfony2-coding-standard).

You may be interested in [PHP Coding Standards Fixer](https://github.com/fabpot/PHP-CS-Fixer).

### Installation

``` bash
$ pear install PHP_CodeSniffer
$ cd `pear config-get php_dir`/PHP/CodeSniffer/Standards
$ git clone git://github.com/opensky/Symfony2-coding-standard.git Symfony2
$ phpcs --config-set default_standard Symfony2
```

### Usage

``` bash
$ phpcs src/
```

**Happy coding** !


## Things you might want to work on:

- Add tests! This package is still undergoing huge refactorings in short periods of time (this is why the version hasn't reached `1.0` yet),
  but tests should be completed now to solidify the codebase.
- Improving the way data is validated before sending it to Slack (whilst creating a request), and receiving it from Slack (whilst creating a response)
- Further extend documentation, especially the [library's](https://github.com/cleentfaar/slack).

