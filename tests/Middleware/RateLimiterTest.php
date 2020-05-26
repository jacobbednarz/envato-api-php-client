<?php

use Envato\Middleware\RateLimiter;
use PHPUnit\Framework\TestCase;

final class RateLimiterTest extends TestCase {
  private $rateLimiter;

  public function setUp() {
    $this->rateLimiter = new RateLimiter();
  }

  public function tearDown() {
    // Don't allow this lock file to be carried between tests.
    if (file_exists($this->rateLimiter->fullSystemPathToLockFile())) {
      unlink($this->rateLimiter->fullSystemPathToLockFile());
    }
  }

  public function testIsThrottled() {
    $throttled = $this->rateLimiter->setThrottle(1);
    $this->assertTrue($this->rateLimiter->isThrottled());
  }

  public function testThrottlerIsRemovedAfterTimeout() {
    $throttled = $this->rateLimiter->setThrottle(0);
    $this->assertFileExists($this->rateLimiter->fullSystemPathToLockFile());
    sleep(1); // This is bad and needs some time travelling lib
    $this->assertFalse($this->rateLimiter->isThrottled());
  }

  public function testThrottleIsOffByDefault() {
    $this->assertFalse($this->rateLimiter->isThrottled());
  }

  public function testSettingAThrottler() {
    $throttled = $this->rateLimiter->setThrottle(1);
    $this->assertTrue($this->rateLimiter->isThrottled());
    $this->assertFileExists($this->rateLimiter->fullSystemPathToLockFile());
  }

  public function testTemporarySystemPathMatches() {
    $this->assertContains(sys_get_temp_dir(), $this->rateLimiter->fullSystemPathToLockFile());
    $this->assertContains(
      $this->rateLimiter->rateLimitFileName,
      $this->rateLimiter->fullSystemPathToLockFile()
    );
  }
}
