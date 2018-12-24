<?php

use Psr\Http\Message\ServerRequestInterface;

$repository = $app->service('categoryCost.repository');

$app
    ->get(
        '/login', function () use ($app) {
            $view = $app->service('view.renderer');
            return $view->render('auth/login.html.twig');
        }, 'auth.show'
    )

    ->post(
        '/login', function (ServerRequestInterface $request) use ($app, $repository) {
            $view = $app->service('view.renderer');
            $auth = $app->service('auth');
            $data = $request->getParsedBody();
            $result = $auth->login($data);

            if(!$result) {
                return $view->render('auth/login.html.twig');
            }

            return $app->route('categoryCosts.list');

        }, 'auth.login'
    )

    ->get(
        '/logout', function () use ($app) {
            $app->service('auth')->logout();

            return $app->route('auth.show');
        }, 'auth.logout'
    );

$app->before(
    function () use ($app) {
        $route = $app->service('route');
        $auth = $app->service('auth');
        $routesWhiteList = [
        'auth.show',
        'auth.login'
        ];

        if(!in_array($route->name, $routesWhiteList) && !$auth->check()) {
            return $app->redirect('/login');
        }
    }
);