<?php

use Psr\Http\Message\ServerRequestInterface;

$repository = $app->service('user.repository');

$app
    ->get(
        '/', function () use ($app) {
            return $app->route('users.list');
        }
    )

    ->get(
        '/users', function () use ($app, $repository) {
            $view = $app->service('view.renderer');

            $users = $repository->all();

            return $view->render(
                'users/list.html.twig', [
                'users' => $users
                ]
            );
        }, 'users.list'
    )

    ->get(
        '/users/new', function () use ($app, $repository) {
            $view = $app->service('view.renderer');

            return $view->render('users/create.html.twig');
        }, 'users.new'
    )

    ->post(
        '/users/store', function (ServerRequestInterface $request) use ($app, $repository) {
            $data = $request->getParsedBody();
            $repository->create($data);

            return $app->route('users.list');
        }, 'users.store'
    )

    ->get(
        '/users/{id}/edit', function (ServerRequestInterface $request) use ($app, $repository) {
            $view = $app->service('view.renderer');
            $user = $repository->find($request->getAttribute('id'));

            return $view->render(
                'users/edit.html.twig', [
                'user' => $user
                ]
            );
        }, 'users.edit'
    )

    ->post(
        '/users/{id}/update', function (ServerRequestInterface $request) use ($app, $repository) {
            $id = $request->getAttribute('id');
            $data = $request->getParsedBody();
            $repository->update($id, $data);

            return $app->route('users.list');
        }, 'users.update'
    )

    ->get(
        '/users/{id}/show', function (ServerRequestInterface $request) use ($app, $repository) {
            $view = $app->service('view.renderer');
            $user = $repository->find($request->getAttribute('id'));

            return $view->render(
                'users/show.html.twig', [
                'user' => $user
                ]
            );
        }, 'users.show'
    )

    ->get(
        '/users/{id}/delete', function (ServerRequestInterface $request) use ($app, $repository) {
            $id = $request->getAttribute('id');
            $repository->delete($id);

            return $app->route('users.list');
        }, 'users.delete'
    );