<?php
namespace CROFin\Plugins;

use CROFin\ServiceContainerInterface;

interface PluginInterface
{
    public function register(ServiceContainerInterface $container);
}