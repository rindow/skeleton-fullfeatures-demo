<?php
namespace Acme\MyApp\Tests\Tools;

use PHPUnit\Framework\TestCase;
use Rindow\Container\ModuleManager;

abstract class AbstractTestCase extends TestCase
{
	protected $output;
	protected $entryPoint;
	protected $moduleManager;
    protected $sessionMode;

	public function setup() : void
	{
		$this->moduleManager = $this->getModuleManager();
        $this->setupDatabase();
		$this->entryPoint = '/index.php';
        $config = $this->moduleManager->getConfig();
        $this->sessionMode = (isset($config['module_manager']['modules']['Rindow\Web\Session\Module']) &&
                $config['module_manager']['modules']['Rindow\Web\Session\Module']) ? true:false;
	}

    public function getModuleManager()
    {
        $config = $this->getConfig();
        $moduleManager = new ModuleManager($config);
        return $moduleManager;
    }

    public function getConfig()
    {
        $config = require TEST_TARGET_APPLICATION_PATH.'/config/webapp.config.php';
        $config['module_manager']['enableCache'] = false;
        if(method_exists($this, 'getUnitTestConfig'))
            $config = array_replace_recursive($config,$this->getUnitTestConfig());
        return $config;
    }

    public function setupDatabase()
    {
    }

    public function dispatch($path,$method=null,$postValues=null,$cookies=null)
    {
        $this->moduleManager = $this->getModuleManager();
        $env = $this->moduleManager->getServiceLocator()->get('Rindow\Web\Http\Message\TestModeEnvironment');
        $env->_SERVER['SCRIPT_NAME'] = $this->entryPoint;
        $env->_SERVER['REQUEST_URI'] = $path;
        $env->_SERVER['REQUEST_METHOD'] = $method ? strtoupper($method) : 'GET';
        $env->_COOKIE = $cookies;
        if(strtoupper($method) == 'POST') {
            foreach ($postValues as $key => $value) {
                $env->_POST[$key] = $value;
            }
        }
        $serviceLocator = $this->moduleManager->getServiceLocator();

        if($this->sessionMode) {
            $session = $serviceLocator->get('Rindow\Web\Session\DefaultSession');
            if(isset($this->sessionData))
                $session->import($this->sessionData);
        }

        $serviceLocator->get('Rindow\Web\Mvc\DefaultApplication');
        $this->output = $this->moduleManager->run('Rindow\Web\Mvc\Module');
        //foreach ($session->getCreatedContainerNames() as $name) {
        //    $serviceLocator->get($name)->flush();
        //}
        if($this->sessionMode) {
            $this->sessionData = $session->export();
        }
        return $this->output;
    }

    public function match($string)
    {
        return (strpos($this->output->getBody()->getContents(),$string)!==false);
    }

    public function extract($pattern)
    {
        $count = preg_match($pattern, $this->output->getBody()->getContents(), $matchs);
        if($count)
            return $matchs;
        else
            return false;
    }

    public function getContents()
    {
        return $this->output->getBody()->getContents();
    }

    public function statusCode()
    {
        return $this->output->getStatusCode();
    }

    public function redirectPath()
    {
        $headers =  $this->output->getHeaders();
        if(!isset($headers['Location']))
            return null;

        if(is_string($headers['Location']))
            return $headers['Location'];
        else
            return $headers['Location'][0];
    }
}
