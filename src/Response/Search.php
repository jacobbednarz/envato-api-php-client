<?php

namespace Envato\Response;

class Search {
  protected $data;

  public function __construct($response) {
    $this->data = json_decode($response->getBody()->getContents(), true);
  }

  public function results() {
    return !empty( $this->data['matches'] ) ? $this->data['matches'] : [];
  }

  public function count() {
    return count( $this->data['matches'] );
  }

}
