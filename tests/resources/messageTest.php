<?php
class messageTest extends BaseTester
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
    // $result = $a->create("message", array(
    //   "receiver" => array(
    //     "id" => 14,
    //     "resource_uri" => "/v1/merchant/14/"
    //   ),
    //   "sender" => array(
    //     "id" => $user->id,
    //     "resource_uri" => "/v1/user/" . $user->id . "/"
    //   ),
    //   "subject" => "Hello",
    //   "body" => "Votre commande est prête."
    // ));
    // $this->assertEmpty($result);
    // var_dump($resut);
  }

}
