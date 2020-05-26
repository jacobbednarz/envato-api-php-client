<?php

namespace Envato\Response;

class WhoAmI {

  protected $data;

  public function __construct($response) {
    $this->data = json_decode($response->getBody()->getContents());
  }

  public function clientId() {
    return $this->data->clientId;
  }

  public function userId() {
    return $this->data->userId;
  }

  public function scopes() {
    return $this->data->scopes;
  }

  public function ttl() {
    return $this->data->ttl;
  }
}
