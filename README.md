# envato-api-php-client

A sane PHP SDK for interacting with the Envato API.

### Usage

```php
require 'vendor/autoload.php';

use Envato\ApiClient;

$client = ApiClient::factory(array(
  'token' => 'xxxxxxxxxxxxxxxxxxxx'
));

var_dump($client->whoami()->userId());
// int(5777395)
```

### Coverage

What to know what endpoints are covered? Check out the types of [`Response`](https://github.com/jacobbednarz/envato-api-php-client/tree/master/src/Response)
classes available. These classes are a one to one mapping of the API endpoints.

### Testing

You can run the test suite using the following command:

```
$ ./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
```

Please. Please. Please. Ensure all changes have tests. If not, there is a good
chance untested functionality will be broken without knowing about it.

This project uses PHP Codesniffer to enforce standards in CI. You can run it 
locally using:

```
$ ./vendor/bin/phpcs --standard=phpcs.xml src/
```
