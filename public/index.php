<?php

use CROFin\Application;
use CROFin\Plugins\AuthPlugin;
use CROFin\Plugins\DbPlugin;
use CROFin\Plugins\RoutePlugin;
use CROFin\Plugins\ViewPlugin;
use CROFin\ServiceContainer;

require_once __DIR__ . '/../vendor/autoload.php';

$serviceContainer = new ServiceContainer();
$app = new Application($serviceContainer);

$app->plugin(new RoutePlugin());
$app->plugin(new ViewPlugin());
$app->plugin(new DbPlugin());
$app->plugin(new AuthPlugin());

require_once __DIR__ . '/../src/controllers/category-costs.php';
require_once __DIR__ . '/../src/controllers/users.php';
require_once __DIR__ . '/../src/controllers/auth.php';

$app->start();