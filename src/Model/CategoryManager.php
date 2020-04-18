<?php
namespace Acme\MyApp\Model;

use Interop\Lenient\Transaction\Annotation\TransactionManagement;
use Interop\Lenient\Transaction\Annotation\TransactionAttribute;
use Interop\Lenient\Container\Annotation\Inject;
use Interop\Lenient\Container\Annotation\Named;
use Rindow\Stdlib\Entity\EntityTrait;
use Acme\MyApp\Entity\Category;

/**
* @Named
* @TransactionManagement
*/
class CategoryManager extends AbstractManager
{
    use EntityTrait;
    /**
    * @Inject({@Named("Acme\MyApp\Repository\CategoryRepository")})
    */
    protected $categoryRepository;

    /**
     * @TransactionAttribute("required")
     */
    public function findAll($pagination=false)
    {
        return $this->findAllEntities($this->categoryRepository,$pagination);
    }

    /**
     * @TransactionAttribute("required")
     */
    public function update(Category $formData)
    {
        $category = $this->categoryRepository->findById($formData->id);
        $category->name  = $formData->name;
        $this->categoryRepository->save($category);
    }

    /**
     * @TransactionAttribute("required")
     */
    public function create(Category $category)
    {
        $category->id = null;
        $this->categoryRepository->save($category);
    }

    /**
     * @TransactionAttribute("required")
     */
    public function delete($id)
    {
        $category = $this->categoryRepository->findById($id);
        if(!$category)
            throw new \RuntimeException('not found');
        $this->categoryRepository->deleteById($category->id);
    }

    /**
     * @TransactionAttribute("required")
     */
    public function findByPrimaryKey($id)
    {
        return $this->categoryRepository->findById($id);
    }
}
