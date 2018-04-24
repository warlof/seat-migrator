![SeAT](http://i.imgur.com/aPPOxSK.png)
# SeAT Upgrader

[![Code Climate](https://codeclimate.com/github/warlof/seat-migrator/badges/gpa.svg)](https://codeclimate.com/github/warlof/seat-upgrader)
[![Latest Stable Version](https://poser.pugx.org/warlof/seat-migrator/v/stable)](https://packagist.org/packages/warlof/seat-upgrader)
[![Total Downloads](https://poser.pugx.org/warlof/seat-migrator/downloads)](https://packagist.org/packages/warlof/seat-upgrader)
[![Latest Unstable Version](https://poser.pugx.org/warlof/seat-migrator/v/unstable)](https://packagist.org/packages/warlof/seat-upgrader)
[![License](https://poser.pugx.org/warlof/seat-migrator/license)](https://packagist.org/packages/warlof/seat-upgrader)

# Supported version
This package is able to handle upgrade between following versions :
 - 2.0.0 to 3.0.0
   - Origin detailed package version
     - eveseat/seat 2.0.0
     - eveseat/eveapi 2.0.14
     - eveseat/notifications 2.0.11
     - eveseat/web 2.0.19
     - eveseat/services 2.0.13
     - eveseat/api 2.0.10
   - Target detailed package versions
     - eveseat/seat 3.0.0
     - eveseat/eveapi 3.0.0
     - eveseat/notifications 3.0.0
     - eveseat/web 3.0.0
     - eveseat/services 3.0.0
     - eveseat/api 3.0.0

# Usage instructions
Version of this package are strongly tied to the SeAT version which should be migrate.

There, while you're installing it, you have to specify the proper version.

Upgrader version will always match with SeAT core version in order to avoid any troubles.

## Process

1. Connect on the server where the SeAT to be migrate is installed
2. Move to the seat directory (usually `/var/www/seat`)
3. Import the package with the proper version (for v2 `composer require warlof/seat-upgrader:2.0.0`)
4. Add package into project by appending `Seat\Upgrader\UpgraderServiceProvider::class,` in `providers` array from `/config/app.php`
5. Publish package files using `php artisan vendor:publish --force`
6. Run migration script using `php artisan migrate` which will append a migration flag to all tables
7. Once the package has been installed, use `php artisan seat:schema:upgrade` to run the upgrade process
8. Follow the prompt and take a cup of tea (if you have a large database, plan for a few of them) :)

## Requirement
In order to use this package, you need a working installation from both source and target SeAT version.

Both Installation may be on different server, it's handled by the script.

Database from both installation must be reachable from the server where the package is installed (source).
