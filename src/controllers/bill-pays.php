<?php

use Psr\Http\Message\ServerRequestInterface;

$repository = $app->service('billPay.repository');

$app
    ->get('/bill-pays', function() use($app, $repository) {
        $view = $app->service('view.renderer');

        $auth = $app->service('auth');

        $bills = $repository->findByField('user_id', $auth->user()->getId());

        return $view->render('billPays/list.html.twig', [
            'bills' => $bills
        ]);
    }, 'billPays.list')

    ->get('/bill-pays/new', function() use($app, $repository){
        $view = $app->service('view.renderer');
        $auth = $app->service('auth');
        $categoryRepository = $app->service('categoryCost.repository');
        $categories = $categoryRepository->findByField('user_id', $auth->user()->getId());

        return $view->render('billPays/create.html.twig', [
            'categories' => $categories
        ]);
    }, 'billPays.new')

    ->post('/bill-pays/store', function(ServerRequestInterface $request) use($app, $repository) {
        $data = $request->getParsedBody();
        $auth = $app->service('auth');
        $categoryRepository = $app->service('categoryCost.repository');

        $data['user_id'] = $auth->user()->getId();
        $data['date_launch'] = dateParse($data['date_launch']);
        $data['value'] = numberParse($data['value']);
        $data['category_cost_id'] = $categoryRepository->findOneBy([
            'id' => $data['category_cost_id'],
            'user_id' => $auth->user()->getId()
        ])->id;
        $repository->create($data);

        return $app->route('billPays.list');
    }, 'billPays.store')

    ->get('/bill-pays/{id}/edit', function(ServerRequestInterface $request) use($app, $repository){
        $view = $app->service('view.renderer');
        $auth = $app->service('auth');

        $bill = $repository->findOneBy([
            'id' => $request->getAttribute('id'),
            'user_id' => $auth->user()->getId()
        ]);

        $auth = $app->service('auth');
        $categoryRepository = $app->service('categoryCost.repository');
        $categories = $categoryRepository->findByField('user_id', $auth->user()->getId());

        return $view->render('billPays/edit.html.twig', [
            'bill' => $bill,
            'categories' => $categories
        ]);
    }, 'billPays.edit')

    ->post('/bill-pays/{id}/update', function(ServerRequestInterface $request) use($app, $repository) {
        $data = $request->getParsedBody();
        $auth = $app->service('auth');
        $categoryRepository = $app->service('categoryCost.repository');

        $data['user_id'] = $auth->user()->getId();
        $data['date_launch'] = dateParse($data['date_launch']);
        $data['value'] = numberParse($data['value']);
        $data['category_cost_id'] = $categoryRepository->findOneBy([
            'id' => $data['category_cost_id'],
            'user_id' => $auth->user()->getId()
        ])->id;
        $repository->update([
            'id' => $request->getAttribute('id'),
            'user_id' => $auth->user()->getId()
        ], $data);

        return $app->route('billPays.list');
    }, 'billPays.update')

    ->get('/bill-pays/{id}/show', function(ServerRequestInterface $request) use($app, $repository){
        $view = $app->service('view.renderer');
        $auth = $app->service('auth');

        $bill = $repository->findOneBy([
            'id' => $request->getAttribute('id'),
            'user_id' => $auth->user()->getId()
        ]);

        return $view->render('billPays/show.html.twig', [
            'bill' => $bill
        ]);
    }, 'billPays.show')

    ->get('/bill-pays/{id}/delete', function(ServerRequestInterface $request) use($app, $repository){
        $auth = $app->service('auth');

        $repository->delete([
            'id' => $request->getAttribute('id'),
            'user_id' => $auth->user()->getId()
        ]);

        return $app->route('billPays.list');
    }, 'billPays.delete');