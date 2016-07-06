# jasmin-web
Simple package that allow work with jasmin-sms settings with php. Allow change settings for outer services.

## Installation 

Require this package in your `composer.json` file:

`"haegemon/jasmin-web": "dev-master"`

...then run `composer update` to download the package to your vendor directory.

## Usage

Package base on telnet connection on jasmin admin service. Package translate php object property to console commands for jasmin admin service. Package allow show all, create, update, delete entities. 

Realise base part of working with User, Group, Connector, Filter (User filter, Connector filter), MtRouter (Static router), Morouter (Static router).

## Examples:

You can find some expamples at folder [examples](https://github.com/haegemon/jasmin-web/tree/master/examples).

## Test

All test based on phpunit. You should set environment variables for set connection params:
- jasmin_admin_username - Username for test connection
- jasmin_admin_password - Password for test connection
- jasmin_admin_host - Host for test connection
- jasmin_admin_port - port for test connection