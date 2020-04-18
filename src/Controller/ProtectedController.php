<?php
namespace Acme\MyApp\Controller;

use Interop\Lenient\Web\Router\Annotation\Controller;
use Interop\Lenient\Web\Router\Annotation\RequestMapping;
use Interop\Lenient\Container\Annotation\Named;
use Interop\Lenient\Container\Annotation\Inject;
use Interop\Lenient\Security\Authorization\Annotation\AccessControlled;
use Interop\Lenient\Security\Authorization\Annotation\RolesAllowed;
use Interop\Lenient\Security\Authorization\Annotation\FullyAuthenticated;

use Rindow\Stdlib\Entity\EntityTrait;

/**
* @Controller
* @RequestMapping(value="/protected",ns="Acme\MyApp")
* @Named
* @AccessControlled
*/
class ProtectedController
{
    use EntityTrait;
    /**
    * @Inject({@Named("Rindow\Security\Core\Authentication\DefaultSecurityContext")})
    */
    protected $securityContext;
    /**
    * @Inject({@Named("Rindow\Web\Session\DefaultFlashBag")})
    */
    protected $flashBag;
    /**
    * @Inject({@Named("Rindow\Web\Security\Authentication\DefaultFormAuthenticationServices")})
    */
    protected $formAuthenticationServices;
    /**
    * @Inject({@Named("Rindow\Web\Mvc\DefaultUrlGenerator")})
    */
    protected $urlGenerator;

    protected function getRoutes()
    {
        return [
            'publicpage'     => 'protected',
            'protectedpage'  => 'protected/protected',
            'fullauthpage'   => 'protected/fullauth',
            'logout'         => 'protected/logout',
            'product'        => 'product',
            'apidemo'        => 'apidemo',
        ];
    }

    protected function getCurrentUsername()
    {
        $auth = $this->securityContext->getAuthentication();
        return $auth->getName();
    }

    /**
    * @RequestMapping(value="/", name="protected", middlewares={"view"}, view="protected/index")
    */
    public function indexAction($request,$response,$args)
    {
        $variables['route'] = $this->getRoutes();
        $variables['flashMessages'] = $this->flashBag;
        return $variables;
    }

    /**
    * @RequestMapping(value="/protected", name="protected/protected", middlewares={"view"}, view="protected/protected")
    * @RolesAllowed({"USER"})
    */
    public function protectedAction($request,$response,$args)
    {
        $variables['route'] = $this->getRoutes();
        $variables['username'] = $this->getCurrentUsername();
        $variables['flashMessages'] = $this->flashBag;
        $variables['logout'] = $this->formAuthenticationServices->getLogoutUrl(
            $this->urlGenerator->fromRoute('protected'));;
        return $variables;
    }

    /**
    * @RequestMapping(value="/fullauth", name="protected/fullauth", middlewares={"view"}, view="protected/fullauth")
    * @RolesAllowed({"USER"})
    * @FullyAuthenticated
    */
    public function fullauthAction($request,$response,$args)
    {
        $variables['route'] = $this->getRoutes();
        $variables['username'] = $this->getCurrentUsername();
        $variables['flashMessages'] = $this->flashBag;
        $variables['logout'] = $this->formAuthenticationServices->getLogoutUrl(
            $this->urlGenerator->fromRoute('protected'));;
        return $variables;
    }
}
