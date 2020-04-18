<?php
namespace Acme\MyApp\Repository;

use Interop\Lenient\Container\Annotation\Inject;
use Interop\Lenient\Container\Annotation\Named;
use Interop\Lenient\Dao\Repository\DataMapper;
use Rindow\Stdlib\Entity\EntityTrait;
use Acme\MyApp\Entity\Product;
use Acme\MyApp\Entity\Category;
//use Acme\MyApp\Entity\Color;

/**
* @Named
*/
class ProductMapper implements DataMapper
{
    use EntityTrait;

    /**
    * @Inject({@Named("Acme\MyApp\Hydrator")})
    */
    protected $hydrator;

    /**
    * @Inject({@Named("Acme\MyApp\Repository\CategoryRepository")})
    */
    protected $categoryRepository;

    protected $categoryCache = [];

    /**
     * @return array|object entity
     */
    public function map($data)
    {
        $product = new Product();
        $this->hydrator->hydrate($data,$product);
        if($product->category) {
            if(isset($this->categoryCache[strval($product->category)])) {
                $product->category = $this->categoryCache[strval($product->category)];
            } else {
                $category = $this->categoryRepository->findById($product->category);
                $this->categoryCache[strval($product->category)] = $category;
                $product->category = $category;
            }
        }
        //if($product->colors) {
        //    $colors = [];
        //    foreach ($product->colors as $key => $colorId) {
        //        $color = new Color();
        //        $color->id = $key;
        //        $color->product = $product;
        //        $color->color = $colorId;
        //        $colors[] = $color;
        //    }
        //    $product->colors = $colors;
        //}
        return $product;
    }

    /**
     * @return array        data
     */
    public function demap($entity)
    {
        $entity = $this->hydrator->extract($entity,['id','category','name','colors']);
        if($entity['category'] instanceof Category) {
            $categoryId = $entity['category']->id;
            $entity['category'] = $categoryId;
        }
        return $entity;
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
