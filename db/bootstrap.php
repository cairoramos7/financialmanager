<?php

use CROFin\Application;
use CROFin\Plugins\AuthPlugin;
use CROFin\Plugins\DbPlugin;
use CROFin\ServiceContainer;

$serviceContainer = new \CROFin\ServiceContainer();
$app = new \CROFin\Application($serviceContainer);

$serviceContainer = new ServiceContainer();
$app = new Application($serviceContainer);

$app->plugin(new DbPlugin());
$app->plugin(new AuthPlugin());

return $app;