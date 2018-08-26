<?php

use Psr\Http\Message\ServerRequestInterface;

$repository = $app->service('billReceive.repository');

$app
    ->get('/bill-receives', function() use($app, $repository) {
        $view = $app->service('view.renderer');

        $auth = $app->service('auth');

        $bills = $repository->findByField('user_id', $auth->user()->getId());

        return $view->render('billReceives/list.html.twig', [
            'bills' => $bills
        ]);
    }, 'billReceives.list')

    ->get('/bill-receives/new', function() use($app, $repository){
        $view = $app->service('view.renderer');

        return $view->render('billReceives/create.html.twig');
    }, 'billReceives.new')

    ->post('/bill-receives/store', function(ServerRequestInterface $request) use($app, $repository) {
        $data = $request->getParsedBody();
        $auth = $app->service('auth');

        $data['user_id'] = $auth->user()->getId();
        $data['date_launch'] = dateParse($data['date_launch']);
        $data['value'] = numberParse($data['value']);
        $repository->create($data);

        return $app->route('billReceives.list');
    }, 'billReceives.store')

    ->get('/bill-receives/{id}/edit', function(ServerRequestInterface $request) use($app, $repository){
        $view = $app->service('view.renderer');
        $auth = $app->service('auth');

        $bill = $repository->findOneBy([
            'id' => $request->getAttribute('id'),
            'user_id' => $auth->user()->getId()
        ]);

        return $view->render('billReceives/edit.html.twig', [
            'bill' => $bill
        ]);
    }, 'billReceives.edit')

    ->post('/bill-receives/{id}/update', function(ServerRequestInterface $request) use($app, $repository) {
        $data = $request->getParsedBody();
        $auth = $app->service('auth');

        $data['user_id'] = $auth->user()->getId();
        $data['date_launch'] = dateParse($data['date_launch']);
        $data['value'] = numberParse($data['value']);
        $repository->update([
            'id' => $request->getAttribute('id'),
            'user_id' => $auth->user()->getId()
        ], $data);

        return $app->route('billReceives.list');
    }, 'billReceives.update')

    ->get('/bill-receives/{id}/show', function(ServerRequestInterface $request) use($app, $repository){
        $view = $app->service('view.renderer');
        $auth = $app->service('auth');

        $bill = $repository->findOneBy([
            'id' => $request->getAttribute('id'),
            'user_id' => $auth->user()->getId()
        ]);

        return $view->render('billReceives/show.html.twig', [
            'bill' => $bill
        ]);
    }, 'billReceives.show')

    ->get('/bill-receives/{id}/delete', function(ServerRequestInterface $request) use($app, $repository){
        $auth = $app->service('auth');

        $repository->delete([
            'id' => $request->getAttribute('id'),
            'user_id' => $auth->user()->getId()
        ]);

        return $app->route('billReceives.list');
    }, 'billReceives.delete');