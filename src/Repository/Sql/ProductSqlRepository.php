<?php
namespace Acme\MyApp\Repository\Sql;

use Rindow\Database\Dao\Repository\GenericSqlRepository;
use Rindow\Database\Dao\Exception;

class ProductSqlRepository extends GenericSqlRepository
{
    protected $tableName = 'demo_products';
    protected $colorsTableName = 'demo_colors';

    public function setTableName($tableName)
    {
        if($tableName==null)
            return;
        parent::setTableName($tableName);
    }

    public function setColorsTableName($colorsTableName)
    {
        if($colorsTableName==null)
            return;
        $this->colorsTableName = $colorsTableName;
    }

    protected function cascadedFieldConfig()
    {
        $config = parent::cascadedFieldConfig();
        array_push($config,[
              'property'=>'colors',
              'tableName'=>$this->colorsTableName,
              'masterIdName'=>'product',
              'fieldName'=>'color',
        ]);
        return $config;
    }
/*
    protected function getPty($object,$name)
    {
        if(is_array($object)) {
            return $object[$name];
        } elseif (is_object($object)) {
            return $object->$name;
        } else {
            throw new Exception\InvalidArgumentException('Must be array or object.');
        }
    }

    protected function setPty(&$object,$name,$value)
    {
        if(is_array($object)) {
            $object[$name] = $value;
        } elseif (is_object($object)) {
            return $object->$name = $value;
        } else {
            throw new Exception\InvalidArgumentException('Must be array or object.');
        }
    }

    public function demap($entity)
    {
        $entity = parent::demap($entity);
        if(is_array($entity)) {
            unset($entity['colors']);
        } elseif(is_object($entity)) {
            $entity = clone $entity;
            unset($entity->colors);
        } else {
            throw new Exception\InvalidArgumentException('Must be array or object.');
        }

        return $entity;
    }

    public function map($data)
    {
        $data = parent::map($data);
        $productId = $this->getPty($data,'id');
        $cursor = $this->getTableOperations()->find($this->colorsTableName,array('product'=>$productId));
        $colors = [];
        foreach($cursor as $row) {
            if(!isset($data['colors']))
            $colors = $row['color'];
        }
        $this->setPty($data,'colors',$colors);
        return $data;
    }

    protected function postCreate($entity)
    {
        parent::postCreate($entity);
        if(is_object($entity)) {
            $entity = ['id'=>$entity->id, 'colors'=>$entity->colors];
        }
        $this->createCascadedField(
            $entity,'colors',$this->colorsTableName,'product','color');
    }

    protected function postUpdate($entity)
    {
        parent::postUpdate($entity);
        if(is_object($entity)) {
            $entity = ['id'=>$entity->id, 'colors'=>$entity->colors];
        }
        $this->updateCascadedField(
            $entity,'colors',$this->colorsTableName,'product','color');
    }

    protected function preDelete($filter)
    {
        parent::preDelete($filter);
        $this->deleteCascadedField($filter,$this->colorsTableName,'product');
    }
*/
}
