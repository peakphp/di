<?php

namespace Peak\Bedrock\Application;

use Peak\Bedrock\Application\Bootstrapper;
use Peak\Bedrock\Application\Config;
use Peak\Bedrock\Application\Routing;
use Peak\Bedrock\Controller\FrontController;

/**
 * Load the framework objects, application bootstrap and front controller.
 */
class Kernel
{
    /**
     * Framework version
     */
    const VERSION = '2.11.0';
    
    /**
     * App bootstrap object if exists
     * @var Application\Bootstrap
     */
    public $bootstrap;

    /**
     * App object front controller
     * @var Controller\FrontController
     */
    public $front;

    /**
     * App routing object
     * @var Application\Routing
     */
    public $routing;
    
    /**
     * App config
     * @var Application\Config
     */
    protected $config = null;

    /**
     * Start framework
     */
    public function __construct(Config $config, Routing $routing)
    {
        // application config
        $this->config = $config;

        // application routing
        $this->routing = $routing;

        $this->loadBootstrap();
        $this->loadFront();
    }

    /**
     * Reload application bootstrapper and front for a module
     *
     * @return $this
     */
    public function reload()
    {
        $this->loadBootstrap();
        $this->loadFront();
        return $this;
    }

    /**
     * Load and store application Bootstrapper
     *
     * @param string $prefix Bootstrap class prefix name if exists
     */
    private function loadBootstrap()
    {
        $cname = $this->config->get('ns').'\Bootstrap';
        $this->bootstrap = (class_exists($cname)) ? new $cname() : new Bootstrapper();
    }

    /**
     * Load and store application Front Controller
     */
    private function loadFront()
    {
        $cname = $this->config->get('ns').'\Front';
        $this->front = (class_exists($cname)) ? new $cname() : new FrontController();
    }

    /**
     * Start front dispatching of a request
     *
     * @param  mixed $request if specified, force the request,
     *         otherwise, it will use server request uri
     * @return $this
     */
    public function run($request = null)
    {
        $this->routing->loadRequest($request);
        $this->front->route = $this->routing->getRoute();

        $this->front->preDispatch();
        $this->front->dispatch();
        $this->front->postDispatch();

        return $this;
    }

    /**
     * Call front controller render() method
     */
    public function render()
    {
        $this->front->preRender();
        $this->front->controller->render();
        $this->front->postRender();
    }
}
