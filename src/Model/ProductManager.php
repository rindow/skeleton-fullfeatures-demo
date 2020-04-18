<?php
namespace Acme\MyApp\Model;

use Interop\Lenient\Transaction\Annotation\TransactionManagement;
use Interop\Lenient\Transaction\Annotation\TransactionAttribute;
use Interop\Lenient\Container\Annotation\Inject;
use Interop\Lenient\Container\Annotation\Named;
use Rindow\Stdlib\Entity\EntityTrait;
use Acme\MyApp\Entity\Product;

/**
* @Named
* @TransactionManagement
*/
class ProductManager extends AbstractManager
{
    use EntityTrait;
    /**
    * @Inject({@Named("Acme\MyApp\Repository\ProductRepository")})
    */
    protected $productRepository;

    /**
    * @Inject({@Named("Acme\MyApp\Repository\CategoryRepository")})
    */
    protected $categoryRepository;

    /**
     * @TransactionAttribute("required")
     */
    public function findAll($pagination=false)
    {
        return $this->findAllEntities($this->productRepository,$pagination);
    }

    /**
     * @TransactionAttribute("required")
     */
    public function update(Product $formData)
    {
        $product = $this->productRepository->findById($formData->id);
        $product->name  = $formData->name;
        if(!($product->category instanceof Category) ||
                strval($product->category->id) != strval($formData->categoryForm)) {
            $product->category = $this->categoryRepository->findById($formData->categoryForm);
        }
        //$product->colorsForm = $formData->colorsForm;
        $product->colors = $formData->colors;
        //$product->updateColorsByFormData();
        $this->productRepository->save($product);
    }

    /**
     * @TransactionAttribute("required")
     */
    public function create(Product $product)
    {
        $product->id = null;
        $product->category = $this->categoryRepository->findById($product->categoryForm);
        if(!$product->category)
            throw new \RuntimeException('category not found');
        //$product->updateColorsByFormData();
        $this->productRepository->save($product);
    }

    /**
     * @TransactionAttribute("required")
     */
    public function delete($id)
    {
        $product = $this->productRepository->findById($id);
        if(!$product)
            throw new \RuntimeException('not found');
        $this->productRepository->deleteById($product->id);
    }

    /**
     * @TransactionAttribute("required")
     */
    public function findByPrimaryKey($id)
    {
        return $this->productRepository->findById($id);
    }

    /**
     * @TransactionAttribute("required")
     */
    public function loadDataForForm($product)
    {
        if($product->categoryForm)
            $product->category = $this->categoryRepository->findById($product->categoryForm);
        $product->categoryOptions = $this->categoryRepository->findAll();
    }
}
