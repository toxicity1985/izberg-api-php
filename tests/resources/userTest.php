<?php
class UserTest extends BaseTester
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

  public function testGetInbox()
	{
    \VCR\VCR::insertCassette('testGetInbox');

		$a = $this->getIzberg();
		$user = $a->get("user");
    $messsages = $user->getInbox();
		$this->assertEmpty($messsages);
	}

  public function testGetOutbox()
	{
    \VCR\VCR::insertCassette('testGetOutbox');

		$a = $this->getIzberg();
		$user = $a->get("user");
    $messsages = $user->getOutbox();
		$this->assertCount(14,$messsages);
	}
}
