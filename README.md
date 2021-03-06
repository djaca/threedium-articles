# Threedium articles

## Installation

### Prerequisites

* To run this project, you must have PHP 7 installed.
* You should setup a host on your web server for your local domain. For this you could also configure Laravel Homestead or Valet.

### Step 1

Begin by cloning this repository to your machine, and installing all Composer & NPM dependencies.

```bash
git clone git@github.com:djaca/threedium-articles.git
cd threedium-articles && composer install && yarn
php artisan articles:install
php artisan migrate --seed
yarn dev
```

### Step 2

Next, boot up a server and visit site. If using a tool like Laravel Valet, of course the URL will default to `http://threedium-articles.test`. 

## Test
```
vendor/bin/phpunit
```
