<?php

namespace Envato\Response;

class WhoAmI {
  protected $data;

  public function __construct($response) {
    $this->data = json_decode($response->getBody()->getContents());
  }

  public function userId() {
    return $this->data->userId;
  }
}
