<?php

use Envato\ApiClient;
use PHPUnit\Framework\TestCase;

final class ApiClientTest extends TestCase {
  public function setUp() {
    $this->client = ApiClient::factory(array(
      'token' => 'notarealtoken'
    ));
  }

  public function testClientApiTokenIsRequired() {
    $this->expectException(Envato\Exception\MissingClientTokenException::class);
    ApiClient::factory(array());
  }

  public function testClientHasRequestTimeout() {
    $this->assertArrayHasKey('timeout', $this->client->getConfig());
  }

  public function testClientHasConnectTimeout() {
    $this->assertArrayHasKey('connect_timeout', $this->client->getConfig());
  }

  public function testClientVerifiesConnection() {
    $this->assertEquals($this->client->getConfig()['verify'], TRUE);
  }

  public function testClientDoesNotFollowRedirects() {
    $this->assertEquals($this->client->getConfig()['allow_redirects'], FALSE);
  }

  public function testClientHasUserAgent() {
    $this->assertContains(
      'Envato PHP SDK/',
      $this->client->getConfig()['headers']['User-Agent']
    );
  }

  public function testClientAllowsOverriddingExistingConfigurationKeys() {
    $local_client = ApiClient::factory(array(
      'timeout' => '999',
      'token' => 'anotherkey',
    ));

    $this->assertEquals($local_client->getConfig()['timeout'], '999');
  }

  public function testClientAllowsOverriddingExistingHeaders() {
    $local_client = ApiClient::factory(array(
      'token' => 'anotherkey',
      'headers' => array(
        'User-Agent' => 'Super Duper Client',
      ),
    ));

    $this->assertEquals(
      $local_client->getConfig()['headers']['User-Agent'],
      'Super Duper Client'
    );
  }
}
