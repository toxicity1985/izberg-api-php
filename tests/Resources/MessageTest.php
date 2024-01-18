<?php

namespace Tests\Resources;

use Tests\BaseTester;

class MessageTest extends BaseTester
{
  /**
   * @before
   */
  public function startRecording()
  {
    \VCR\VCR::turnOn();
    \VCR\VCR::configure()->setStorage('json');
  }

  /**
   * @after
   */
  public function stopRecording()
  {
    // To stop recording requests, eject the cassette
    \VCR\VCR::eject();
    // Turn off VCR to stop intercepting requests
    \VCR\VCR::turnOff();
  }

  /**
   * Tests
   */
  public function testSendMessage()
  {
    \VCR\VCR::insertCassette('testSendMessage');

    $a = $this->getIzberg();
    $user = $a->get("user");
    $result = $a->create("message", array(
      "receiver" => array(
        "id" => 77,
        "resource_uri" => "/v1/merchant/77/"
      ),
      "sender" => array(
        "id" => $user->id,
        "resource_uri" => "/v1/user/" . $user->id . "/"
      ),
      "subject" => "Hello",
      "body" => "Votre commande est prête."
    ));
    $this->assertNotEmpty($result);
    $this->assertEquals($result->status, "unread");
  }

  public function testReadAndCloseMessage()
  {
    \VCR\VCR::insertCassette('testReadAndCloseMessage');

    $a = $this->getIzberg();
    $user = $a->get("user");
    $message = $a->create("message", array(
      "receiver" => array(
        "id" => 77,
        "resource_uri" => "/v1/merchant/77/"
      ),
      "sender" => array(
        "id" => $user->id,
        "resource_uri" => "/v1/user/" . $user->id . "/"
      ),
      "subject" => "Hello",
      "body" => "Votre commande est prête."
    ));
    $result = $message->read();
    $this->assertEquals($result->status, "read");

    $result = $message->close();
    $this->assertEquals($result->status, "closed");
  }

  public function testGetAllMessages()
  {
    \VCR\VCR::insertCassette('testGetAllMessages');

    $a = $this->getIzberg();
    $messages = $a->get_list("message");
    $this->assertCount(14, $messages);
  }

}
