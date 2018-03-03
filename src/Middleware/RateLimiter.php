<?php

namespace Envato\Middleware;

class RateLimiter {
  /**
   * @var string Filename of the lock file that is used to hold the unix
   * timestamp of rate limit timeouts.
   */
  public $rate_limit_file_name = 'envato_api_sdk.lock';

  /**
   * Checks if the client is currently throttled from making requests.
   *
   * @return boolean Whether the client is prohibited from initiating new
   * connections.
   */
  public function isThrottled() {
    $rate_limit_timeout_value = $this->rateLimitTimeoutValue();

    if (time() > $rate_limit_timeout_value) {
      $this->removeThrottle();
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * Create a rate limit flag.
   *
   * @param int $retry_after_value Time in seconds that the client has been rate
   * limited for.
   */
  public function setThrottle($retry_after_value) {
    $retry_after_timestamp = time() + $retry_after_value;

    if (!$this->temporary_directory_writable()) {
      return FALSE;
    }

    $lock_file = fopen($this->full_system_path_to_lock_file(), 'w');
    fwrite($lock_file, $retry_after_timestamp);
    fclose($lock_file);
  }

  /**
   * Defines the full system path to the lock file.
   *
   * @return string
   */
  public function full_system_path_to_lock_file() {
    return sys_get_temp_dir() . '/' . $this->rate_limit_file_name;
  }

  /**
   * Clean up throttle flag.
   */
  protected function removeThrottle() {
    if (!$this->lock_file_is_accessible()) {
      return FALSE;
    }

    unlink($this->full_system_path_to_lock_file());
  }

  /**
   * Get the defined rate limit value.
   *
   * @return integer Unix timestamp of when the client is able to initiate
   * requests again. Returns 0 if the lock file isn't accessible as a means of
   * bypassing the rate limiting.
   */
  protected function rateLimitTimeoutValue() {
    if (!$this->lock_file_is_accessible()) {
      return 0;
    }

    return (int) file_get_contents($this->full_system_path_to_lock_file());
  }

  /**
   * Check if the system temporary directory is writable.
   *
   * @return boolean
   */
  protected function temporary_directory_writable() {
    return is_writable(dirname($this->full_system_path_to_lock_file()));
  }

  /**
   * Checks the lock file can be managed by the PHP process.
   *
   * @return boolean
   */
  protected function lock_file_is_accessible() {
    return (file_exists($this->full_system_path_to_lock_file()) && is_readable($this->full_system_path_to_lock_file()));
  }
}
