<?php

namespace Envato\Middleware;

class RateLimiter {
  /**
   * @var string Filename of the lock file that is used to hold the unix
   * timestamp of rate limit timeouts.
   */
  public $rateLimitFileName = 'envato_api_sdk.lock';

  /**
   * Checks if the client is currently throttled from making requests.
   *
   * @return boolean Whether the client is prohibited from initiating new
   * connections.
   */
  public function isThrottled() {
    $rateLimitTimeoutValue = $this->rateLimitTimeoutValue();

    if (time() > $rateLimitTimeoutValue) {
      $this->removeThrottle();
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * Create a rate limit flag.
   *
   * @param int $retryAfterValue Time in seconds that the client has been rate
   * limited for.
   */
  public function setThrottle($retryAfterValue) {
    $retyAfterTimestamp = time() + $retryAfterValue;

    if (!$this->temporary_directory_writable()) {
      return FALSE;
    }

    $lock_file = fopen($this->fullSystemPathToLockFile(), 'w');
    fwrite($lock_file, $retyAfterTimestamp);
    fclose($lock_file);
  }

  /**
   * Defines the full system path to the lock file.
   *
   * @return string
   */
  public function fullSystemPathToLockFile() {
    return sys_get_temp_dir() . '/' . $this->rateLimitFileName;
  }

  /**
   * Clean up throttle flag.
   */
  protected function removeThrottle() {
    if (!$this->lockFileIsAccessible()) {
      return FALSE;
    }

    unlink($this->fullSystemPathToLockFile());
  }

  /**
   * Get the defined rate limit value.
   *
   * @return integer Unix timestamp of when the client is able to initiate
   * requests again. Returns 0 if the lock file isn't accessible as a means of
   * bypassing the rate limiting.
   */
  protected function rateLimitTimeoutValue() {
    if (!$this->lockFileIsAccessible()) {
      return 0;
    }

    return (int) file_get_contents($this->fullSystemPathToLockFile());
  }

  /**
   * Check if the system temporary directory is writable.
   *
   * @return boolean
   */
  protected function temporary_directory_writable() {
    return is_writable(dirname($this->fullSystemPathToLockFile()));
  }

  /**
   * Checks the lock file can be managed by the PHP process.
   *
   * @return boolean
   */
  protected function lockFileIsAccessible() {
    return (file_exists($this->fullSystemPathToLockFile()) && is_readable($this->fullSystemPathToLockFile()));
  }
}
