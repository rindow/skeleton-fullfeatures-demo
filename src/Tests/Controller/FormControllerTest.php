<?php
namespace Acme\MyApp\Tests\Controller\ControllerTest;

use Acme\MyApp\Tests\Tools\AbstractTestCase;
use Acme\MyApp\Repository\SchemaManager;

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

    public function testForm()
    {
        $this->dispatch('/category');
        $this->assertEquals(200,$this->statusCode());
        $this->assertTrue($this->match('Category List'));
        $this->assertTrue($this->match('Add New'));

        $this->dispatch('/category/edit');
        $this->assertEquals(200,$this->statusCode());
        $this->assertTrue($this->match('New Category'));

        // new Category
        $name = 'cat1';//substr(md5(rand()),0,8);
        $this->dispatch('/category/edit','post',array('name'=>$name));
        $this->assertEquals(302,$this->statusCode());
        $this->assertEquals('/category',$this->redirectPath());
        $category = $this->getCategoryRepository()->findOne(['name'=>'cat1']);
        $categoryId = strval($category->id);

        $this->dispatch('/product');
        $this->assertEquals(200,$this->statusCode());
        $this->assertTrue($this->match('Product List'));
        $this->assertTrue($this->match('Add New'));

        $this->dispatch('/product/edit');
        $this->assertEquals(200,$this->statusCode());
        $this->assertTrue($this->match('New Product'));

        // product name is too long
        $name = 'abcdefghij over length ';
        $this->dispatch('/product/edit','post',array('category'=>$categoryId, 'name'=>$name,'colors'=>array('1')));
        $this->assertEquals(200,$this->statusCode());
        $this->assertTrue($this->match('New Product'));
        $this->assertTrue($this->match('small class="help-block"') || $this->match('small class="error"'));

        // new product
        $name = 'p1';//substr(md5(rand()),0,8);
        $this->dispatch('/product/edit','post',array('category'=>$categoryId,'name'=>$name,'colors'=>array('1')));
        $this->assertEquals(302,$this->statusCode());
        $this->assertEquals('/product',$this->redirectPath());
        $product = $this->getProductRepository()->findOne(['name'=>'p1']);
        $productId = strval($product->id);

        // duplicate product name
        $name = 'p1';//substr(md5(rand()),0,8);
        $this->dispatch('/product/edit','post',array('category'=>$categoryId,'name'=>$name,'colors'=>array('1')));
        $this->assertEquals(200,$this->statusCode());
        $this->assertTrue($this->match('New Product'));
        $this->assertTrue($this->match('small class="help-block"') || $this->match('small class="error"'));

        $this->dispatch('/product');
        $this->assertEquals(200,$this->statusCode());
        //$this->assertTrue($this->match($name));
        $matchs = $this->extract('@href="/product/edit/'.$productId.'"@');
        $this->assertTrue(($matchs!==false));
        $this->dispatch('/product/delete/'.$productId);
        $this->assertEquals(302,$this->statusCode());
        $this->assertEquals('/product',$this->redirectPath());
    }
}
