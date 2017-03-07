# Requirement

VirtualBox 5.1.x
Vagrant >= 1.9.0
PHP 5
Composer

# Setup

1. Pull source
   `git clone git@github.com:VLMH/coffee-shop.git`
2. Go to app folder
   `cd coffee-shop`
3. Install dependencies
   `composer install`
4. Generate `Vagrantfile` and `Homestead.yaml`
   `php vendor/bin/homestead make`
5. Copy `.env` file
   `cp .env.example .env`
5. Start VM
   `vagrant up`
6. SSH to VM
   `vagrant ssh`
7. Create DB
   `createdb -U homestead -h 127.0.0.1 -O homestead coffeeshop`
8. Run migration
   `cd ./Code/coffee-shop && php artisan migrate`
9. Set `/etc/hosts`
   `coffee-shop.app 192.168.10.10`
10. Go to app http://coffee-shop.app

# PayPal test credit card

- AMEX 347149799668709 04/2022
- VISA 4032035073037590 04/2022
- MASTERCARD 5110921578761093 04/2022
- DISCOVER 6011869674198918 04/2022


# Braintree test credit card numbers

https://developers.braintreepayments.com/reference/general/testing/php#credit-card-numbers

# Testing

Run test with `cd path/to/app && vendor/bin/phpunit tests`
