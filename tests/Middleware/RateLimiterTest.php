<?php

use Envato\Middleware\RateLimiter;
use PHPUnit\Framework\TestCase;

final class RateLimiterTest extends TestCase {
  private $rate_limiter;

  public function setUp() {
    $this->rate_limiter = new RateLimiter();
  }

  public function tearDown() {
    // Don't allow this lock file to be carried between tests.
    if (file_exists($this->rate_limiter->full_system_path_to_lock_file())) {
      unlink($this->rate_limiter->full_system_path_to_lock_file());
    }
  }

  public function testIsThrottled() {
    $throttled = $this->rate_limiter->setThrottle(1);
    $this->assertTrue($this->rate_limiter->isThrottled());
  }

  public function testThrottlerIsRemovedAfterTimeout() {
    $throttled = $this->rate_limiter->setThrottle(0);
    $this->assertFileExists($this->rate_limiter->full_system_path_to_lock_file());
    sleep(1); // This is bad and needs some time travelling lib
    $this->assertFalse($this->rate_limiter->isThrottled());
  }

  public function testThrottleIsOffByDefault() {
    $this->assertFalse($this->rate_limiter->isThrottled());
  }

  public function testSettingAThrottler() {
    $throttled = $this->rate_limiter->setThrottle(1);
    $this->assertTrue($this->rate_limiter->isThrottled());
    $this->assertFileExists($this->rate_limiter->full_system_path_to_lock_file());
  }

  public function testTemporarySystemPathMatches() {
    $this->assertContains(sys_get_temp_dir(), $this->rate_limiter->full_system_path_to_lock_file());
    $this->assertContains(
      $this->rate_limiter->rate_limit_file_name,
      $this->rate_limiter->full_system_path_to_lock_file()
    );
  }
}
