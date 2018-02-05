<?php

namespace Envato;

use GuzzleHttp\Client;

class ApiClient extends Client {
  public static function factory($config = []) {
    if (empty($config['token'])) {
      throw new Exceptions\MissingClientTokenException('Missing required API token.');
    }

    $defaults = [
      'connect_timeout' => '3',
      'timeout' => '10',
      'verify' => TRUE,
      'allow_redirects' => FALSE,
      'headers' => [
        'Authorization' => "Bearer {$config['token']}",
        'User-Agent' => 'Envato PHP SDK/0.1',
      ],
    ];

    $combined_configuration = array_merge($defaults, $config);
    $client = new static($combined_configuration);

    return $client;
  }

  public function whoami() {
    $request = $this->get('https://api.envato.com/whoami');
    return new Response\WhoAmI($request);
  }

  public function account() {
    $request = $this->get('https://api.envato.com/v1/market/private/user/account.json');
    return new Response\Account($request);
  }

  public function search( $query = [] ) {
      // todo: Do we need to verify the query params are valid before passing them to the API?
    $request = $this->get('https://api.envato.com/v1/discovery/search/search/item', ['query' => $query]);
    return new Response\Search($request);
  }
}
