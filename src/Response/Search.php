<?php

namespace Envato\Response;

class Search {
  protected $data;

  public function __construct($response) {
      // todo: Can we throw an exception here if json_decode fails (i.e. API returns invalid JSON like the the 500 unavaiable HTML error)
    $this->data = json_decode($response->getBody()->getContents(), TRUE);
  }

  public function results() {
    return !empty( $this->data['matches'] ) ? $this->data['matches'] : [];
  }

  public function count() {
    return count( $this->data['matches'] );
  }

}
