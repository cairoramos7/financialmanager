<?php
declare(strict_types=1);
namespace CROFin\Plugins;

use CROFin\Auth\JasnyAuth;
use CROFin\ServiceContainerInterface;
use Interop\Container\ContainerInterface;
use CROFin\Auth\Auth;

class AuthPlugin implements PluginInterface
{
    public function register(ServiceContainerInterface $container)
    {
        $container->addLazy(
            'jasny.auth', function (ContainerInterface $container) {
                return new JasnyAuth($container->get('user.repository'));
            }
        );

        $container->addLazy(
            'auth', function (ContainerInterface $container) {
                return new Auth($container->get('jasny.auth'));
            }
        );
    }
}