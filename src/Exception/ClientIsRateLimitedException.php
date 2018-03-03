<?php

namespace Envato\Exception;

class ClientIsRateLimitedException extends \Exception {
  public function __construct() {
    parent::__construct('Too many requests');
  }
}
