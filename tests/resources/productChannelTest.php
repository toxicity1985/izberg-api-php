<?php
class productChannelTest extends BaseTester
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
  public function testGetOutputUsingOutputFile()
  {
    \VCR\VCR::insertCassette('testGetOutputUsingOutputFile');

    $mock = $this->getMock('Izberg\Helper', array('readFromUrl'), array());
    $mock->expects($this->any())
	    ->method('readFromUrl')
      ->with(
         "https://d1uyhd0hkrx9pt.cloudfront.net/channels/2016/04/20160413144852_channel_389_4.xml"
      )
	    ->will($this->returnValue(true));

    $a = $this->getIzberg(array(), array('getHelper'));

    $a->method("getHelper")->will($this->returnValue($mock));

    $application = $a->getCurrentApplication();
    $application->get_channel()->output(array(), "test.xml");
  }

  public function testGetOutputUsingViewer()
  {
    \VCR\VCR::insertCassette('testGetOutputUsingViewer');

    $a = $this->getIzberg(array(), array("getCurrentApplication"));
    $channel_mock = $this->getMock('Izberg\Resource\ProductChannel', array('getViewer', 'getName'), array());
    $channel_mock->expects($this->once())
	    ->method('getViewer')
      ->with(array(), "test.xml")
	    ->will($this->returnValue(true));

    $channel_mock->expects($this->any())
	    ->method('getName')
	    ->will($this->returnValue("product_channel"));
    $channel_mock->id = 389;

    $app_mock = $this->getMock('Izberg\Resource\Application', array('get_channel'), array());
    $app_mock->expects($this->any())
	    ->method('get_channel')
	    ->will($this->returnValue($channel_mock));
    $app_mock->id = 9;

    $a->expects($this->any())
        ->method('getCurrentApplication')
        ->will($this->returnValue($app_mock));

    $application = $a->getCurrentApplication();
    $channel = $application->get_channel()->output(array(), "test.xml");
  }


}
