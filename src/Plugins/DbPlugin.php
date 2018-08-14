<?php
declare(strict_types=1);
namespace CROFin\Plugins;

use CROFin\Models\BillReceive;
use CROFin\Models\CategoryCost;
use CROFin\Models\User;
use CROFin\Repository\RepositoryFactory;
use CROFin\ServiceContainerInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use Interop\Container\ContainerInterface;

class DbPlugin implements PluginInterface
{
    public function register(ServiceContainerInterface $container)
    {
        $capsule = new Capsule();
        $config = include __DIR__ . '/../../config/db.php';
        $capsule->addConnection($config['development']);
        $capsule->bootEloquent();

        $container->add('repository.factory', new RepositoryFactory());

        $container->addLazy('categoryCost.repository', function(ContainerInterface $container) {
            return $container->get('repository.factory')->factory(CategoryCost::class);
        });

        $container->addLazy('billReceive.repository', function(ContainerInterface $container) {
            return $container->get('repository.factory')->factory(BillReceive::class);
        });

        $container->addLazy('user.repository', function(ContainerInterface $container) {
            return $container->get('repository.factory')->factory(User::class);
        });
    }
}