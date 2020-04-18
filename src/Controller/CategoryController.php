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
use Acme\MyApp\Entity\Category;

/**
* @Controller
* @RequestMapping(value="/category",ns="Acme\MyApp")
* @Named
*/
class CategoryController
{
    use EntityTrait;
    /**
    * @Inject({@Named("Acme\MyApp\Model\CategoryManager")})
    */
    protected $itemManager;
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
            'index'    => 'category',
            'new'      => 'category/edit',
            'edit'     => 'category/edit',
            'update'   => 'category/edit',
            'delete'   => 'category/delete',
            'category' => 'category',
            'product'  => 'product',
            'protectedpage'  => 'protected',
            'apidemo'  => 'apidemo',
        ];
    }

    /**
    * @RequestMapping(value="/", name="category", middlewares={"view"}, view="category/index")
    */
    public function indexAction($request,$response,$args)
    {
        $params = new Dict($request->getQueryParams());
        $page = $params->get('page',1);
        if($page==0)
            $page = 1;
        $paginator = $this->itemManager->findAll($pagination=true);
        $paginator->setPage($page);
        $variables['items'] = $paginator;
        $variables['route'] = $this->getRoutes();
        $variables['paginatorTemplate'] = $this->paginatorTemplate;
        $variables['flashMessages'] = $this->flashBag;
        return $variables;
    }

    /**
     * @RequestMapping(value="/edit", method="get", parameters={"id"}, middlewares={"view"}, view="category/edit", name="category/edit")
     */
    public function editAction($request,$response,$args)
    {
        $args = new Dict($args);
        $id = $args->get('id');
        if($id===null) {
            $item = new Category();
            $new = true;
        } else {
            $item = $this->itemManager->findByPrimaryKey($id);
            if(!$item) {
                $this->flashBag->add('notice','Not found. Create new category.');
                $item = new Category();
                $new = true;
            } else {
                $new = false;
            }
        }
        $formctx = $this->formBuilder->build($item);
        $formctx->setAttribute('action',$this->url->fromRoute('category/update'));
        $variables['form'] = $formctx->getForm();
        $variables['route']  = $this->getRoutes();
        $variables['new']  = $new;
        $variables['paginatorTemplate'] = $this->paginatorTemplate;
        $variables['flashMessages'] = $this->flashBag;
        return $variables;
    }

    /**
     * @RequestMapping(value="/edit", method="post", name="category/update", middlewares={"view"}, view="category/edit")
     */
    public function updateAction($request,$response,$args)
    {
        $item = new Category();
        $formctx = $this->formBuilder->build($item)
                    ->setRequestToData($request->getParsedBody());
        if(!$formctx->isValid()) {
            $formctx->setAttribute('action',$this->url->fromRoute('category/update'));
            $variables['form'] = $formctx->getForm();
            $variables['route'] = $this->getRoutes();
            $variables['new'] = empty($item->id);
            $variables['paginatorTemplate'] = $this->paginatorTemplate;
            $variables['flashMessages'] = $this->flashBag;
            return $variables;
        }
        // Update
        $new = empty($item->id);
        try {
            if($new) {
                $this->itemManager->create($item);
                $this->flashBag->add('notice','New Category saved.');
            } else {
                $this->itemManager->update($item);
                $this->flashBag->add('notice','Changes saved.');
            }
        } catch(DuplicateKeyException $e) {
            $formctx->setAttribute('action',$this->url->fromRoute('category/update'));
            $variables['form'] = $formctx->getForm();
            $variables['form']['name']->errors[] = 'The name has already been used.';
            $variables['route'] = $this->getRoutes();
            $variables['new']  = $new;
            $variables['paginatorTemplate'] = $this->paginatorTemplate;
            $variables['flashMessages'] = $this->flashBag;
            return $variables;
        }
        return $this->redirect->toRoute($response,'category');
    }

    /**
     * @RequestMapping(value="/delete", method="get", parameters={"id"}, name="category/delete")
     */
    public function deleteAction($request,$response,$args)
    {
        $args = new Dict($args);
        $id = $args->get('id');
        $this->itemManager->delete($id);
        $this->flashBag->add('notice','Category Deleted.');
        // Redirect
        return $this->redirect->toRoute($response,'category');
    }
}
