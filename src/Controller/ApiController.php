<?php
namespace Demo\Angular\Controller;

use Interop\Lenient\Web\Router\Annotation\Controller;
use Interop\Lenient\Web\Router\Annotation\RequestMapping;
use Interop\Lenient\Container\Annotation\Named;
use Interop\Lenient\Container\Annotation\Inject;
use Interop\Lenient\Container\Annotation\NamedConfig;
use Rindow\Stdlib\Entity\EntityTrait;
use Rindow\Stdlib\Dict;
use Acme\MyApp\Entity\Category;

/**
* @Controller
* @RequestMapping(value="/api",ns="Acme\MyApp")
* @Named
*/
class ApiController
{
    use EntityTrait;
    /**
    * @Inject({@Named("Acme\MyApp\Model\CategoryManager")})
    */
    protected $categoryManager;
    /**
    * @Inject({@Named("Acme\MyApp\Hydrator")})
    */
    protected $hydrator;
    /**
    * @Inject({@Named("Rindow\Validation\DefaultValidator")})
    */
    protected $validator;
    /**
    * @Inject({@Named("Logger")})
    */
    protected $logger;

    protected function logHeader($request)
    {
        foreach ($request->getHeaders() as $hdname => $header) {
            $this->logger->debug($hdname.':'.implode(',',$header));
        }
    }

    protected function hydrate($data,$entity)
    {
        $data = json_decode($data,true);
        $this->hydrator->hydrate($data,$entity);
        $errors = $this->validator->validate($entity);

        if(count($errors)==0) {
            return $entity;
        }
        $array = [];
        foreach($errors as $error) {
            $array[] = $error;
        }
        throw new \Exception(implode(',',$array));
    }

    /**
    * @RequestMapping(value="/category", method="get", headers={accept="application/json"}, parameters={"id"}, middlewares={"csrf"}, name="api/get-category")
    * RequestMapping(value="/category", method="get", parameters={"id"}, middlewares={"csrf"}, view="public", name="api/get-category")
    * RequestMapping(value="/category", method="get", parameters={"id"}, middlewares={"csrf"}, name="api/get-category")
    */
    public function getCategoryAction($request,$response,$args)
    {
        $this->logger->debug('ApiDemo:GET');
        $this->logHeader($request);
        $params = new Dict($args);

        if($params->get('id')) {
            $item = $this->categoryManager->findByPrimaryKey($params->get('id'));
            $response->getBody()->write(json_encode([$item]));
        } else {
            $items = $this->categoryManager->findAll();
            $array = [];
            foreach ($items as $item) {
                $array[] = $item;
            }
            $response->getBody()->write(json_encode($array));
        }
        $response = $response->withHeader('Content-Type', 'application/json');
        return $response;
    }

    /**
    * @RequestMapping(value="/category", method="post", headers={accept="application/json"}, middlewares={"csrf"}, view="public", name="api/create-category")
    */
    public function createCategoryAction($request,$response,$args)
    {
        $this->logger->debug('ApiDemo:POST');
        $this->logHeader($request);
        $this->logger->debug('Content-Type:'.implode(',',$request->getHeader('Content-Type')));
        $data = $request->getBody()->getContents();
        $item = new Category();
        $this->hydrate($data,$item);
        $this->logger->debug('create:'.$data);
        $this->categoryManager->create($item);
        return $response;
    }

    /**
    * @RequestMapping(value="/category", method="put", headers={accept="application/json"}, middlewares={"csrf"}, name="api/update-category")
    */
    public function updateCategoryAction($request,$response,$args)
    {
        $this->logger->debug('ApiDemo:PUT');
        $this->logHeader($request);
        $data = $request->getBody()->getContents();
        $item = new Category();
        $this->hydrate($data,$item);
        $this->logger->debug('update:'.$data);
        $this->categoryManager->update($item);
        return $response;
    }

    /**
    * @RequestMapping(value="/category", method="delete", headers={accept="application/json"}, parameters={"id"}, middlewares={"csrf"}, name="api/delete-category")
    */
    public function deleteCategoryAction($request,$response,$args)
    {
        $this->logger->debug('ApiDemo:DELETE');
        $this->logHeader($request);
        $params = new Dict($args);
        $this->logger->debug('delete:'.$params->get('id'));
        $this->categoryManager->delete($params->get('id'));
        return $response;
    }

    /**
    * @RequestMapping(value="/category", method="options", view="public", middlewares={"csrf"}, name="api/options-category")
    */
    public function optionsCategoryAction($request,$response,$args)
    {
        $this->logger->debug('ApiDemo:OPTIONS');
        $this->logHeader($request);
        return $response;
    }
}
