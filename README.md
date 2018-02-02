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

### Testing

You can run the test suite using the following command:

```
$ ./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
```

Please. Please. Please. Ensure all changes have tests. If not, there is a good
chance untested functionality will be broken without knowing about it.
