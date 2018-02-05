<?php

namespace Envato;

use GuzzleHttp\Client;

class ApiClient extends Client {
  const VERSION    = '0.1';
  const USER_AGENT = 'Envato PHP SDK/' . self::VERSION;
  const BASE_URI   = 'https://api.envato.com';

  public static function factory($config = array()) {
    if (empty($config['token'])) {
      throw new Exception\MissingClientTokenException('Missing required API token.');
    }

    $defaults = array(
      'base_uri' => self::BASE_URI,
      'connect_timeout' => '3',
      'timeout' => '10',
      'verify' => TRUE,
      'allow_redirects' => FALSE,
      'headers' => array(
        'Authorization' => "Bearer {$config['token']}",
        'User-Agent' => self::USER_AGENT,
      ),
    );

    $combined_configuration = array_merge($defaults, $config);
    $client = new static($combined_configuration);

    return $client;
  }

  public function whoami() {
    $request = $this->get('/whoami');
    return new Response\WhoAmI($request);
  }

  public function account() {
    $request = $this->get('/v1/market/private/user/account.json');
    return new Response\Account($request);
  }
}
