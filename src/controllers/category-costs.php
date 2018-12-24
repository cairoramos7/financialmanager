<?php

use Psr\Http\Message\ServerRequestInterface;

$repository = $app->service('categoryCost.repository');

$app
    ->get(
        '/', function () use ($app) {
            return $app->route('categoryCosts.list');
        }
    )

    ->get(
        '/category-costs', function () use ($app, $repository) {
            $view = $app->service('view.renderer');

            $auth = $app->service('auth');

            $categories = $repository->findByField('user_id', $auth->user()->getId());

            return $view->render(
                'categoryCosts/list.html.twig', [
                'categories' => $categories
                ]
            );
        }, 'categoryCosts.list'
    )

    ->get(
        '/category-costs/new', function () use ($app, $repository) {
            $view = $app->service('view.renderer');

            return $view->render('categoryCosts/create.html.twig');
        }, 'categoryCosts.new'
    )

    ->post(
        '/category-costs/store', function (ServerRequestInterface $request) use ($app, $repository) {
            $data = $request->getParsedBody();
            $auth = $app->service('auth');

            $data['user_id'] = $auth->user()->getId();
            $repository->create($data);

            return $app->route('categoryCosts.list');
        }, 'categoryCosts.store'
    )

    ->get(
        '/category-costs/{id}/edit', function (ServerRequestInterface $request) use ($app, $repository) {
            $view = $app->service('view.renderer');
            $auth = $app->service('auth');

            $category = $repository->findOneBy(
                [
                'id' => $request->getAttribute('id'),
                'user_id' => $auth->user()->getId()
                ]
            );

            return $view->render(
                'categoryCosts/edit.html.twig', [
                'category' => $category
                ]
            );
        }, 'categoryCosts.edit'
    )

    ->post(
        '/category-costs/{id}/update', function (ServerRequestInterface $request) use ($app, $repository) {
            $data = $request->getParsedBody();
            $auth = $app->service('auth');

            $data['user_id'] = $auth->user()->getId();
            $repository->update(
                [
                'id' => $request->getAttribute('id'),
                'user_id' => $auth->user()->getId()
                ], $data
            );

            return $app->route('categoryCosts.list');
        }, 'categoryCosts.update'
    )

    ->get(
        '/category-costs/{id}/show', function (ServerRequestInterface $request) use ($app, $repository) {
            $view = $app->service('view.renderer');
            $auth = $app->service('auth');

            $category = $repository->findOneBy(
                [
                'id' => $request->getAttribute('id'),
                'user_id' => $auth->user()->getId()
                ]
            );

            return $view->render(
                'categoryCosts/show.html.twig', [
                'category' => $category
                ]
            );
        }, 'categoryCosts.show'
    )

    ->get(
        '/category-costs/{id}/delete', function (ServerRequestInterface $request) use ($app, $repository) {
            $auth = $app->service('auth');

            $repository->delete(
                [
                'id' => $request->getAttribute('id'),
                'user_id' => $auth->user()->getId()
                ]
            );

            return $app->route('categoryCosts.list');
        }, 'categoryCosts.delete'
    );