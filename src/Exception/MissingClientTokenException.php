<?php

namespace Envato\Exception;

class MissingClientTokenException extends \Exception {
  public function __construct() {
    parent::__construct('Missing required API token.');
  }
}
