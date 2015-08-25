<?php
class categoryTest extends BaseTester
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
  public function testGetCategoriesShouldReturnCategories()
  {
    \VCR\VCR::insertCassette('testGetCategoriesShouldReturnCategories');

    $a = $this->getIzberg();
    $categories = $a->get_list("category");
    $this->assertTrue(is_array($categories));
    $this->assertNotEmpty($categories);
  }

  public function testGetrootCategories()
  {
    \VCR\VCR::insertCassette('testGetrootCategories');

    $a = $this->getIzberg();
    $categories = $a->get_list("category");
    $this->assertTrue(count($categories) > 0);
    $this->assertEquals($categories[0]->get_category_endpoint(), "category");
  }

  public function testGetSubcategories()
  {
    \VCR\VCR::insertCassette('testGetSubcategories');

    $a = $this->getIzberg();
    $category = new Ice\Category();
    $category->id = 1021;
    $subCategories = $category->get_childs();
    $this->assertTrue(count($subCategories) > 0);
  }
}
