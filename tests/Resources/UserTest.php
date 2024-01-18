<?php

namespace Tests\Resources;

use Tests\BaseTester;
use VCR\VCR;

class UserTest extends BaseTester
{
    /**
     * @before
     */
    public function startRecording()
    {
        VCR::turnOn();
        VCR::configure()->setStorage('json');
    }

    /**
     * @after
     */
    public function stopRecording()
    {
        // To stop recording requests, eject the cassette
        VCR::eject();
        // Turn off VCR to stop intercepting requests
        VCR::turnOff();
    }

    public function testGetInbox()
    {
        VCR::insertCassette('testGetInbox');

        $a = $this->getIzberg();
        $user = $a->get("user");
        $messsages = $user->getInbox();
        $this->assertEmpty($messsages);
    }

    public function testGetOutbox()
    {
        VCR::insertCassette('testGetOutbox');

        $a = $this->getIzberg();
        $user = $a->get("user");
        $messsages = $user->getOutbox();
        $this->assertCount(14, $messsages);
    }
}
