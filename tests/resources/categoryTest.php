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
    Izberg\Resource\Category::tearDown();
    Izberg\Resource\ApplicationCategory::tearDown();
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


  public function testGetCategoriesShouldReturnCategories()
  {
    \VCR\VCR::insertCassette('testGetCategoriesShouldReturnCategories');

    $a = $this->getIzberg();
    $categories = $a->get_list("applicationCategory");
    $this->assertTrue(is_array($categories));
    $this->assertNotEmpty($categories);
  }

  public function testGetrootCategories()
  {
    \VCR\VCR::insertCassette('testGetrootCategories');

    $a = $this->getIzberg();
    $categories = $a->get_list("applicationCategory");
    $this->assertTrue(count($categories) > 0);
    $this->assertEquals($categories[0]->get_endpoint(), "application_category");
  }

  public function testGetSubcategories()
  {
    \VCR\VCR::insertCassette('testGetSubcategories');

    $a = $this->getIzberg();
    $category = new Izberg\Resource\Category();
    $category->id = 1021;
    $subCategories = $category->get_childs();
    $this->assertTrue(count($subCategories) > 0);
  }

  public function testCanGetCategories()
  {
    \VCR\VCR::insertCassette('testCanGetCategories');

    $a = $this->getIzberg();
    $categories = $a->get_list("category");
    $this->assertInstanceOf('Izberg\Resource\Category', $categories[0]);
  }

  public function testGetRootApplicationCategories()
  {
    \VCR\VCR::insertCassette('testGetRootApplicationCategories');

    $a = $this->getIzberg();
    $categories = $a->get_list("applicationCategory");
    $this->assertInstanceOf('Izberg\Resource\ApplicationCategory', $categories[0]);
  }

  public function testGetApplicationCategoryChild()
  {
    \VCR\VCR::insertCassette('testGetApplicationCategoryChild');

    $a = $this->getIzberg();
    $categories = $a->get_list("applicationCategory");
    $subcats = $categories[0]->get_childs();
    $this->assertInstanceOf('Izberg\Resource\ApplicationCategory', $subcats[0]);
  }

  public function testGetApplicationCategoryWithoutCallingRoot()
  {
    \VCR\VCR::insertCassette('testGetApplicationCategoryWithoutCallingRoot');
    $a = $this->getIzberg();
    $fakeCat = new Izberg\Resource\ApplicationCategory();
    $fakeCat->id = 5836;

    $subcats = $fakeCat->get_childs();
    $this->assertInstanceOf('Izberg\Resource\ApplicationCategory', $subcats[0]);
  }
}
