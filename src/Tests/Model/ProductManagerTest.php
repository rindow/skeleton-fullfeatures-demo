<?php
namespace Acme\MyApp\Tests\Model\ProductManagerTest;

use Acme\MyApp\Tests\Tools\AbstractTestCase;
use Acme\MyApp\Repository\SchemaManager;

use Acme\MyApp\Entity\Category;
use Acme\MyApp\Entity\Product;
use Interop\Lenient\Dao\Exception\DuplicateKeyException;

class Test extends AbstractTestCase
{
    public function setupDatabase()
    {
        $schemaManager = $this->moduleManager->getServiceLocator()->get(SchemaManager::class);
        try {
            $schemaManager->dropRepository();
        } catch(\Throwable $e) {
            if($e->getMessage()!='ns not found')
                throw $e;
        }
        $schemaManager->createRepository();
        $categoryRepository = $this->moduleManager->getServiceLocator()->get('Acme\\MyApp\\Repository\\CategoryRepository');
        $category = new Category();
        $category->name = 'cat1';
        $categoryRepository->save($category);
        $this->testCategory = $category;
    }

    public function getManager()
    {
        return $this->moduleManager->getServiceLocator()->get('Acme\\MyApp\\Model\\ProductManager');
    }

    public function testCURD()
    {
        $manager = $this->getManager();
        $product = new Product();
        $product->name = 'test';
        $product->categoryForm = $this->testCategory->id;
        //$product->colorsForm = [1,2];
        $product->colors = [1,2];
        // create
        $manager->create($product);
        $this->assertNotNull($product->id);
        // read
        $product2 =  $manager->findByPrimaryKey($product->id);
        $this->assertEquals('test',$product2->name);
        $this->assertEquals($this->testCategory->id,$product2->category->id);
        $this->assertNotEquals(spl_object_id($product),spl_object_id($product2));
        // update
        $product->name = 'test2';
        $manager->update($product);
        $product2 =  $manager->findByPrimaryKey($product->id);
        $this->assertEquals('test2',$product2->name);
        // list
        $cats = $manager->findAll();
        $this->assertInstanceOf('Interop\Lenient\Dao\Query\ResultList',$cats);
        $count = 0;
        foreach ($cats as $product) {
            $this->assertEquals('test2',$product->name);
            $count++;
        }
        $this->assertEquals(1,$count);
        // list with pagination
        $cats = $manager->findAll(true);
        $this->assertInstanceOf('Rindow\Stdlib\Paginator\Paginator',$cats);
        $count = 0;
        foreach ($cats as $product) {
            $this->assertEquals('test2',$product->name);
            $count++;
        }
        $this->assertEquals(1,$count);
        // delete
        $manager->delete($product->id);
        $cats = $manager->findAll();
        $count = 0;
        foreach ($cats as $product) {
            $count++;
        }
        $this->assertEquals(0,$count);
    }

    public function testDuplicate()
    {

        $manager = $this->getManager();
        $product = new Product();
        $product->name = 'test';
        $product->categoryForm = $this->testCategory->id;
        //$product->colorsForm = [1,2];
        $product->colors = [1,2];
        $manager->create($product);

        $product = new Product();
        $product->name = 'test';
        $product->categoryForm = $this->testCategory->id;
        //$product->colorsForm = [1,2];
        $product->colors = [1,2];

        $this->expectException(DuplicateKeyException::class);
        $manager->create($product);
    }
}
