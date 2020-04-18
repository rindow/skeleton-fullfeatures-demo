<?php
namespace Acme\MyApp\Tests\Repository\ProductRepositoryTest;

use Acme\MyApp\Tests\Tools\AbstractTestCase;
use Acme\MyApp\Repository\SchemaManager;
use Acme\MyApp\Entity\Product;
use Acme\MyApp\Entity\Category;
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
    }

    public function getCategoryRepository()
    {
        return $this->moduleManager->getServiceLocator()->get('Acme\\MyApp\\Repository\\CategoryRepository');
    }

    public function getProductRepository()
    {
        return $this->moduleManager->getServiceLocator()->get('Acme\\MyApp\\Repository\\ProductRepository');
    }

    public function testCURD()
    {
        $categoryRepository = $this->getCategoryRepository();
        $category = new Category();
        $category->name = 'category1';
        $categoryRepository->save($category);

        $proudctRepository = $this->getProductRepository();
        $product = new Product();
        $product->name = 'test';
        $product->category = $category;
        // create
        $proudctRepository->save($product);
        $this->assertNotNull($product->id);
        // read
        $product2 =  $proudctRepository->findById($product->id);
        $this->assertEquals('test',$product2->name);
        $this->assertNotEquals(spl_object_id($product),spl_object_id($product2));
        // update
        $product->name = 'test2';
        $proudctRepository->save($product);
        $product2 =  $proudctRepository->findById($product->id);
        $this->assertEquals('test2',$product2->name);
        // list
        $cats = $proudctRepository->findAll();
        $this->assertInstanceOf('Interop\Lenient\Dao\Query\ResultList',$cats);
        $count = 0;
        foreach ($cats as $product) {
            $this->assertEquals('test2',$product->name);
            $count++;
        }
        $this->assertEquals(1,$count);
        // delete
        $proudctRepository->deleteById($product->id);
        $cats = $proudctRepository->findAll();
        $count = 0;
        foreach ($cats as $product) {
            $count++;
        }
        $this->assertEquals(0,$count);
    }

    public function testDuplicate()
    {
        $categoryRepository = $this->getCategoryRepository();
        $category = new Category();
        $category->name = 'category1';
        $categoryRepository->save($category);

        $proudctRepository = $this->getProductRepository();
        $product = new Product();
        $product->name = 'test';
        $product->category = $category;
        $proudctRepository->save($product);

        $proudctRepository = $this->getProductRepository();
        $product = new Product();
        $product->name = 'test';
        $product->category = $category;
        $this->expectException(DuplicateKeyException::class);
        $proudctRepository->save($product);
    }
}
