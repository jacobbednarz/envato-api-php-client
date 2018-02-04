<?php

namespace Envato;

use GuzzleHttp\Client;

class ApiClient extends Client {
  public static function factory($config = array()) {
    if (empty($config['token'])) {
      throw new Exceptions\MissingClientTokenException('Missing required API token.');
    }

    $defaults = array(
      'connect_timeout' => '3',
      'timeout' => '10',
      'verify' => true,
      'allow_redirects' => false,
      'headers' => array(
        'Authorization' => "Bearer {$config['token']}",
        'User-Agent' => 'Envato PHP SDK/0.1',
      ),
    );

    $combined_configuration = array_merge($defaults, $config);
    $client = new static($combined_configuration);

    return $client;
  }

  public function whoami() {
    $request = $this->get('https://api.envato.com/whoami');
    return new Response\WhoAmI($request);
  }

}
