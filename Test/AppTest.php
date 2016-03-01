<?php

namespace Tale\Test\Runtime;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tale\App;
use Tale\App\MiddlewareInterface;

class HelloMiddleware implements MiddlewareInterface
{

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    )
    {
        $response->getBody()->write('Hello ');

        return $next($request, $response);
    }
}

class WorldMiddleware implements MiddlewareInterface
{

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    )
    {
        $response = $next($request, $response);
        $response->getBody()->write('World!');
        return $response;
    }
}

class FuckingMiddleware implements MiddlewareInterface
{

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    )
    {
        $response->getBody()->write('fucking ');
        return $next($request, $response);
    }
}

class AppTest extends \PHPUnit_Framework_TestCase
{

    public function testMiddleware()
    {

        $app = new App();



        $this->assertEquals('Hello fucking World!',
            (string)$app->add(new HelloMiddleware())
                ->add(new WorldMiddleware())
                ->add(new FuckingMiddleware())
                ->run()->getBody()
        );
    }
}