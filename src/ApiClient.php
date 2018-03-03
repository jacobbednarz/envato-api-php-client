<?php

namespace Envato;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Middleware;
use GuzzleHttp\Handler\CurlHandler;
use Envato\Middleware\RateLimiter;

class ApiClient extends Client {
  const VERSION    = '0.1';
  const USER_AGENT = 'Envato PHP SDK/' . self::VERSION;
  const BASE_URI   = 'https://api.envato.com';

  public static function factory($config = array()) {
    if (empty($config['token'])) {
      throw new Exception\MissingClientTokenException;
    }

    $rate_limiter = new RateLimiter();
    $stack = new HandlerStack();
    $stack->setHandler(new CurlHandler());

    $stack->push(
      Middleware::mapRequest(
        function (RequestInterface $request) use ($rate_limiter) {
          if ($rate_limiter->isThrottled()) {
            throw new Exception\ClientIsRateLimitedException;
          } else {
            return $request;
          }
        }
      )
    );

    $stack->push(
      Middleware::mapResponse(
        function (ResponseInterface $response) use ($rate_limiter) {
          if ($response->getStatusCode() == 429) {
            $rate_limiter->setThrottle($response->getHeader('Retry-After')[0]);
            throw new Exception\ClientIsRateLimitedException;
          } else {
            return $response;
          }
        }
      )
    );

    $defaults = array(
      'handler' => $stack,
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
