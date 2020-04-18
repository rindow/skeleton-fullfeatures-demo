<?php
namespace Acme\MyApp\Tests\Model\CategoryManagerTest;

use Acme\MyApp\Tests\Tools\AbstractTestCase;
use Acme\MyApp\Repository\SchemaManager;
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

    public function getManager()
    {
        return $this->moduleManager->getServiceLocator()->get('Acme\\MyApp\\Model\\CategoryManager');
    }

    public function testCURD()
    {
        $manager = $this->getManager();
        $category = new Category();
        $category->name = 'test';
        // create
        $manager->create($category);
        $this->assertNotNull($category->id);
        // read
        $category2 =  $manager->findByPrimaryKey($category->id);
        $this->assertEquals('test',$category2->name);
        $this->assertNotEquals(spl_object_id($category),spl_object_id($category2));
        // update
        $category->name = 'test2';
        $manager->update($category);
        $category2 =  $manager->findByPrimaryKey($category->id);
        $this->assertEquals('test2',$category2->name);
        // list
        $cats = $manager->findAll();
        $this->assertInstanceOf('Interop\Lenient\Dao\Query\ResultList',$cats);
        $count = 0;
        foreach ($cats as $category) {
            $this->assertEquals('test2',$category->name);
            $count++;
        }
        $this->assertEquals(1,$count);
        // list with pagination
        $cats = $manager->findAll(true);
        $this->assertInstanceOf('Rindow\Stdlib\Paginator\Paginator',$cats);
        $count = 0;
        foreach ($cats as $category) {
            $this->assertEquals('test2',$category->name);
            $count++;
        }
        $this->assertEquals(1,$count);
        // delete
        $manager->delete($category->id);
        $cats = $manager->findAll();
        $count = 0;
        foreach ($cats as $category) {
            $count++;
        }
        $this->assertEquals(0,$count);
    }

    public function testDuplicate()
    {
        $manager = $this->getManager();
        $category = new Category();
        $category->name = 'test';
        $manager->create($category);

        $manager = $this->getManager();
        $category = new Category();
        $category->name = 'test';
        $this->expectException(DuplicateKeyException::class);
        $manager->create($category);
    }
}
