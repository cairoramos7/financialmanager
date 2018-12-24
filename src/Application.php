<?php
declare(strict_types=1);

namespace CROFin;

use CROFin\Plugins\PluginInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\Response\SapiEmitter;

/**
 * Class Application
 *
 * @package CROFin
 */
class Application
{
    /**
     * @var ServiceContainerInterface
     */
    private $_serviceContainer;

    /**
     * @var array
     */
    private $_befores = [];

    /**
     * Application constructor.
     *
     * @param $_serviceContainer
     */
    public function __construct(ServiceContainerInterface $_serviceContainer)
    {
        $this->_serviceContainer = $_serviceContainer;
    }

    /**
     * @param  $name
     * @return mixed
     */
    public function service($name)
    {
        return $this->_serviceContainer->get($name);
    }

    /**
     * @param string  $name
     * @param $service
     */
    public function addService(string $name, $service): void
    {
        if (is_callable($service)) {
            $this->_serviceContainer->addLazy($name, $service);
        } else {
            $this->_serviceContainer->add($name, $service);
        }
    }

    /**
     * @param PluginInterface $plugin
     */
    public function plugin(PluginInterface $plugin): void
    {
        $plugin->register($this->_serviceContainer);
    }

    /**
     * @param  $path
     * @param  $action
     * @param  null   $name
     * @return Application
     */
    public function get($path, $action, $name = null): Application
    {
        $routing = $this->service('routing');
        $routing->get($name, $path, $action);
        return $this;
    }

    /**
     * @param  $path
     * @param  $action
     * @param  null   $name
     * @return Application
     */
    public function post($path, $action, $name = null): Application
    {
        $routing = $this->service('routing');
        $routing->post($name, $path, $action);
        return $this;
    }

    /**
     * @param  $path
     * @return ResponseInterface
     */
    public function redirect($path): ResponseInterface
    {
        return new RedirectResponse($path);
    }

    /**
     * @param  string $name
     * @param  array  $params
     * @return ResponseInterface
     */
    public function route(string $name, array $params = []): ResponseInterface
    {
        $generator = $this->service('routing.generator');
        $path = $generator->generate($name, $params);
        return $this->redirect($path);
    }

    /**
     * @param  callable $callback
     * @return Application
     */
    public function before(callable $callback): Application
    {
        array_push($this->_befores, $callback);
        return $this;
    }

    /**
     * @return ResponseInterface|null
     */
    protected function runBefores(): ?ResponseInterface
    {
        foreach ($this->_befores as $callback) {
            $result = $callback($this->service(RequestInterface::class));
            if ($result instanceof ResponseInterface) {
                return $result;
            }
        }

        return null;
    }

    /**
     *
     */
    public function start(): void
    {
        $route = $this->service('route');
        /**
         * @var ServerRequestInterface $request
         */
        $request = $this->service(RequestInterface::class);

        if (!$route) {
            echo "Page not found";
            exit;
        }

        foreach ($route->attributes as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }

        $result = $this->runBefores();
        if ($result) {
            $this->emitResponse($result);
            return;
        }

        $callable = $route->handler;
        $response = $callable($request);
        $this->emitResponse($response);
    }


    /**
     * @param ResponseInterface $response
     */
    protected function emitResponse(ResponseInterface $response): void
    {
        $emitter = new SapiEmitter();
        $emitter->emit($response);
    }
}