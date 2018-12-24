<?php
declare(strict_types=1);
namespace CROFin\Plugins;

use CROFin\Models\BillPay;
use CROFin\Models\BillReceive;
use CROFin\Models\CategoryCost;
use CROFin\Models\User;
use CROFin\Repository\CategoryCostRepository;
use CROFin\Repository\RepositoryFactory;
use CROFin\Repository\StatementRepository;
use CROFin\ServiceContainerInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use Interop\Container\ContainerInterface;

class DbPlugin implements PluginInterface
{
    public function register(ServiceContainerInterface $container)
    {
        $capsule = new Capsule();
        $config = include __DIR__ . '/../../config/db.php';
        $capsule->addConnection($config['default_connection']);
        $capsule->bootEloquent();

        $container->add('repository.factory', new RepositoryFactory());

        $container->addLazy(
            'categoryCost.repository', function () {
                return new CategoryCostRepository();
            }
        );

        $container->addLazy(
            'billReceive.repository', function (ContainerInterface $container) {
                return $container->get('repository.factory')->factory(BillReceive::class);
            }
        );

        $container->addLazy(
            'billPay.repository', function (ContainerInterface $container) {
                return $container->get('repository.factory')->factory(BillPay::class);
            }
        );

        $container->addLazy(
            'user.repository', function (ContainerInterface $container) {
                return $container->get('repository.factory')->factory(User::class);
            }
        );

        $container->addLazy(
            'statement.repository', function () {
                return new StatementRepository();
            }
        );
    }
}