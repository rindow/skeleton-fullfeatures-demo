<?php
namespace Acme\MyApp\Repository;

use Interop\Lenient\Container\Annotation\Inject;
use Interop\Lenient\Container\Annotation\Named;
use Interop\Lenient\Dao\Repository\DataMapper;
use Acme\MyApp\Entity\Category;
use Rindow\Stdlib\Entity\EntityTrait;

/**
* @Named
*/
class CategoryMapper implements DataMapper
{
    use EntityTrait;

    /**
    * @Inject({@Named("Acme\MyApp\Hydrator")})
    */
    protected $hydrator;

    /**
     * @return array|object entity
     */
    public function map($data)
    {
        $category = new Category();
        $this->hydrator->hydrate($data,$category);
        if($category->id)
        return $category;
    }

    /**
     * @return array        data
     */
    public function demap($entity)
    {
        return $this->hydrator->extract($entity,['id','name']);
    }

    /**
     * @return array|object entity
     */
    public function fillId($entity,$id)
    {
        $entity->id = $id;
        return $entity;
    }

    /**
     * @return string       Specify fetch class when table operations
     */
    public function getFetchClass()
    {
        return null;
    }
}
