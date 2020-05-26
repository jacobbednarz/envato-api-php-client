<?php

namespace Envato\Response;

class Account {
  protected $data;

  public function __construct($response) {
    $this->data = json_decode($response->getBody()->getContents());
  }

  public function image() {
    return $this->data->account->image;
  }

  public function firstName() {
    return $this->data->account->firstname;
  }

  public function surname() {
    return $this->data->account->surname;
  }

  public function availableEarnings() {
    return $this->data->account->available_earnings;
  }

  public function totalDeposits() {
    return $this->data->account->total_deposits;
  }

  public function balance() {
    return $this->data->account->balance;
  }

  public function country() {
    return $this->data->account->country;
  }
}
