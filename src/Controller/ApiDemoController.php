<?php
namespace Acme\MyApp\Controller;

use Interop\Lenient\Web\Router\Annotation\Controller;
use Interop\Lenient\Web\Router\Annotation\RequestMapping;
use Interop\Lenient\Container\Annotation\Named;
use Rindow\Stdlib\Dict;

/**
* @Controller
* @RequestMapping(value="/apidemo",ns="Acme\MyApp")
* @Named
*/
class ApiDemoController
{
    protected function getRoutes()
    {
        return [
            'index'    => 'apidemo',
            'apidemo'  => 'apidemo',
            'product'  => 'product',
            'protectedpage'  => 'protected',
        ];
    }
    /**
    * @RequestMapping(value="/", name="apidemo", middlewares={"view"}, view="api/vue")
    */
    public function indexAction($request,$response,$args)
    {
        return [
            'route' => $this->getRoutes(),
        ];
    }
}
