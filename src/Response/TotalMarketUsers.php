<?php

namespace Envato\Response;

class TotalMarketUsers {
  protected $data;

  public function __construct($payload) {
    $this->data = json_decode($payload->getBody()->getContents());
  }

  public function totalMarketUsers() {
    return $this->data->{'total-users'}->total_users;
  }
}
