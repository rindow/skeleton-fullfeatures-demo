<?php
namespace Acme\MyApp\Controller;

use Interop\Lenient\Web\Router\Annotation\Controller;
use Interop\Lenient\Web\Router\Annotation\RequestMapping;
use Interop\Lenient\Container\Annotation\Named;
use Interop\Lenient\Container\Annotation\NamedConfig;
use Interop\Lenient\Container\Annotation\Inject;
use Interop\Lenient\Dao\Exception\DuplicateKeyException;
use Rindow\Stdlib\Entity\EntityTrait;
use Rindow\Stdlib\Dict;
use Acme\MyApp\Entity\Product;
use Acme\MyApp\Entity\Category;

/**
* @Controller
* @RequestMapping(value="/product",ns="Acme\MyApp")
* @Named
*/
class ProductController
{
    use EntityTrait;
    /**
    * @Inject({@Named("Acme\MyApp\Model\ProductManager")})
    */
    protected $itemManager;
    /**
    * @Inject({@Named("Acme\MyApp\Model\CategoryManager")})
    */
    protected $categoryManager;
    /**
    * @Inject({@Named("Rindow\Web\Mvc\DefaultRedirector")})
    */
    protected $redirect;
    /**
    * @Inject({@Named("Rindow\Web\Mvc\DefaultUrlGenerator")})
    */
    protected $url;
    /**
    * @Inject({@Named("Rindow\Web\Form\DefaultFormContextBuilder")})
    */
    protected $formBuilder;
    /**
    * @Inject({@Named("Rindow\Web\Session\DefaultFlashBag")})
    */
    protected $flashBag;
    /**
    * @Inject({@NamedConfig("web::view::templates::paginator")})
    */
    protected $paginatorTemplate;

    protected function getRoutes()
    {
        return [
            'index'    => 'product',
            'new'      => 'product/edit',
            'edit'     => 'product/edit',
            'update'   => 'product/edit',
            'delete'   => 'product/delete',
            'category' => 'category',
            'product'  => 'product',
            'protectedpage'  => 'protected',
            'apidemo'  => 'apidemo',
        ];
    }

    /**
    * @RequestMapping(value="/", name="product", middlewares={"view"}, view="product/index")
    */
    public function indexAction($request,$response,$args)
    {
        $params = new Dict($request->getQueryParams());
        $page = $params->get('page',1);
        if($page==0)
            $page = 1;
        $colors = Product::$colorNames;
        $paginator = $this->itemManager->findAll($pagination=true);
        $paginator->setPage($page);
        $variables['items']  = $paginator;
        $variables['colors'] = $colors;
        $variables['route']  = $this->getRoutes();
        $variables['paginatorTemplate'] = $this->paginatorTemplate;
        $variables['flashMessages'] = $this->flashBag;
        //$deleteButton = new DeleteButton();
        //$formctx = $this->formBuilder->build($deleteButton);
        //$variables['form'] = $formctx->getForm();
        return $variables;
    }

    /**
     * @RequestMapping(value="/edit", method="get", parameters={"id"}, name="product/edit", middlewares={"view"}, view="product/edit")
     */
    public function editAction($request,$response,$args)
    {
        $args = new Dict($args);
        $id = $args->get('id');
        if($id===null) {
            $item = new Product();
            $new = true;
        } else {
            $item = $this->itemManager->findByPrimaryKey($id);
            if(!$item) {
                $this->flashBag->add('notice','Not found. Create new product.');
                $item = new Product();
                $new = true;
            } else {
                $new = false;
            }
        }
        $formctx = $this->formBuilder->build($item);
        $formctx->setAttribute('action',$this->url->fromRoute('product/update'));
        $item->categoryOptions = $this->categoryManager->findAll();
        $variables['form'] = $formctx->getForm();
        $variables['route']  = $this->getRoutes();
        $variables['new']  = $new;
        $variables['paginatorTemplate'] = $this->paginatorTemplate;
        $variables['flashMessages'] = $this->flashBag;
        return $variables;
    }

    /**
     * @RequestMapping(value="/edit", method="post", name="product/update", middlewares={"view"}, view="product/edit")
     */
    public function updateAction($request,$response,$args)
    {
        $item = new Product();
        $formctx = $this->formBuilder->build($item)
                    ->setRequestToData($request->getParsedBody());
        if(!$formctx->isValid()) {
            $this->itemManager->loadDataForForm($item);
            $formctx->setAttribute('action',$this->url->fromRoute('product/update'));
            $variables['form'] = $formctx->getForm();
            $variables['route'] = $this->getRoutes();
            $variables['new'] = empty($item->id);
            $variables['flashMessages'] = $this->flashBag;
            return $variables;
        }
        // Update
        $new = empty($item->id);
        try {
            if($new) {
                $this->itemManager->create($item);
                $this->flashBag->add('notice','New Product saved.');
            } else {
                $this->itemManager->update($item);
                $this->flashBag->add('notice','Changes saved.');
            }
        } catch(DuplicateKeyException $e) {
            $item->categoryOptions = $this->categoryManager->findAll();
            $formctx->setAttribute('action',$this->url->fromRoute('product/update'));
            $variables['form'] = $formctx->getForm();
            $variables['form']['name']->errors[] = 'The name has already been used.';
            $variables['route'] = $this->getRoutes();
            $variables['new']  = $new;
            $variables['paginatorTemplate'] = $this->paginatorTemplate;
            $variables['flashMessages'] = $this->flashBag;
            return $variables;
        }
        return $this->redirect->toRoute($response,'product');
    }

    /**
     * @RequestMapping(value="/delete", method="get", parameters={"id"}, name="product/delete")
     */
    public function deleteAction($request,$response,$args)
    {
        $args = new Dict($args);
        $id = $args->get('id');
        $this->itemManager->delete($id);
        $this->flashBag->add('notice','Product Deleted.');
        // Redirect
        return $this->redirect->toRoute($response,'product');
    }
}
