<?php
require_once('../vendor/autoload.php');

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpKernel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

try
{
    $fileLocator = new FileLocator(array(__DIR__));

    $requestContext = new RequestContext();
    $request = Request::createFromGlobals();
    $requestContext->fromRequest($request);

    $router = new Router(
        new YamlFileLoader($fileLocator),
        './Routes/routes.yml',
        array('cache_dir' => __DIR__.'/cache'),
        $requestContext
    );

    $parameters = $router->match($requestContext->getPathInfo());

    $controllerResolver = new HttpKernel\Controller\ControllerResolver();
    $argumentResolver = new HttpKernel\Controller\ArgumentResolver();

    try {
        $request->attributes->add($parameters);

        $controller = $controllerResolver->getController($request);
        $arguments = $argumentResolver->getArguments($request, $controller);

        $response = call_user_func_array($controller, $arguments);
    } catch (Routing\Exception\ResourceNotFoundException $exception) {
        $response = new Response('Controller not found', 404);
    } catch (Exception $exception) {
        $response = new Response('An error occurred', 500);
    }

    return $response->send();
}
catch (Exception $e)
{
    echo $e->getMessage();
}
