<?php

use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase {
  public function testNothingBlowsUp(){
    $this->assertEquals('a', 'a');
  }
}
