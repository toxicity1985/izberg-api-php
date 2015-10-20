<?php
class applicationTest extends BaseTester
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
  public function testGetChannel()
	{
    \VCR\VCR::insertCassette('testGetChannel');
		$a = $this->getIzberg();
    $application = $a->getCurrentApplication();
    $channel = $application->get_channel();
    $this->assertNotNull($channel->id);
    $this->assertInstanceOf("Izberg\Resource\ProductChannel", $channel);
	}

}
