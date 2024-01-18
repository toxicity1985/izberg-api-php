<?php

namespace Tests\Resources;

use Izberg\Resource\ApplicationCategory;
use Izberg\Resource\Category;
use Tests\BaseTester;
use VCR\VCR;

class CategoryTest extends BaseTester
{
  /**
   * @before
   */
  public function startRecording()
  {
    VCR::turnOn();
    VCR::configure()->setStorage('json');
    Category::tearDown();
    ApplicationCategory::tearDown();
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


  public function testGetCategoriesShouldReturnCategories()
  {
    VCR::insertCassette('testGetCategoriesShouldReturnCategories');

    $a = $this->getIzberg();
    $categories = $a->get_list("applicationCategory");
    $this->assertTrue(is_array($categories));
    $this->assertNotEmpty($categories);
  }

  public function testGetrootCategories()
  {
    VCR::insertCassette('testGetrootCategories');

    $a = $this->getIzberg();
    $categories = $a->get_list("applicationCategory");
    $this->assertTrue(count($categories) > 0);
    $this->assertEquals($categories[0]->get_endpoint(), "application_category");
  }

  public function testGetSubcategories()
  {
    VCR::insertCassette('testGetSubcategories');

    $a = $this->getIzberg();
    $category = new Category();
    $category->id = 1021;
    $subCategories = $category->get_childs();
    $this->assertTrue(count($subCategories) > 0);
  }

  public function testCanGetCategories()
  {
    VCR::insertCassette('testCanGetCategories');

    $a = $this->getIzberg();
    $categories = $a->get_list("category");
    $this->assertInstanceOf('Izberg\Resource\Category', $categories[0]);
  }

  public function testGetRootApplicationCategories()
  {
    VCR::insertCassette('testGetRootApplicationCategories');

    $a = $this->getIzberg();
    $categories = $a->get_list("applicationCategory");
    $this->assertInstanceOf('Izberg\Resource\ApplicationCategory', $categories[0]);
  }

  public function testGetApplicationCategoryChild()
  {
    VCR::insertCassette('testGetApplicationCategoryChild');

    $a = $this->getIzberg();
    $categories = $a->get_list("applicationCategory");
    $subcats = $categories[0]->get_childs();
    $this->assertInstanceOf('Izberg\Resource\ApplicationCategory', $subcats[0]);
  }

  public function testGetApplicationCategoryWithoutCallingRoot()
  {
    VCR::insertCassette('testGetApplicationCategoryWithoutCallingRoot');
    $a = $this->getIzberg();
    $fakeCat = new ApplicationCategory();
    $fakeCat->id = 21709;

    $subcats = $fakeCat->get_childs();
    $this->assertInstanceOf('Izberg\Resource\ApplicationCategory', $subcats[0]);
  }
}
