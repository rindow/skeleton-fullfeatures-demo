<?php
namespace Acme\MyApp\Controller;

use Interop\Lenient\Web\Router\Annotation\Controller;
use Interop\Lenient\Web\Router\Annotation\RequestMapping;
use Interop\Lenient\Container\Annotation\Named;
use Rindow\Stdlib\Dict;

/**
* @Controller
* @RequestMapping(value="/",ns="Acme\MyApp")
* @Named
*/
class IndexController
{
    /**
    * @RequestMapping(value="/", name="home", middlewares={"view"}, view="index/index")
    */
    public function indexAction($request,$response,$args)
    {
        $variables['color'] = null;
        $variables['test'] = '<test>';

        return $variables;
    }

    /**
    * @RequestMapping(value="/color", name="color", parameters={"color"}, middlewares={"view"}, view="index/index")
    */
    public function helloAction($request,$response,$args)
    {
        $args = new Dict($args);
        $color = $args->get('color');

        if($color=='exception')
            throw new \Exception('Exception!!');
        $variables['color'] = $color;
        return $variables;
    }
}
