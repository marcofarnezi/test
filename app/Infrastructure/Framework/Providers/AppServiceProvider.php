<?php

namespace App\Infrastructure\Framework\Providers;

use App\Domain\Database\Transaction;
use Illuminate\Support\ServiceProvider;
use App\Domain\Repository\UserRepository;
use App\Domain\Repository\OrderRepository;
use App\Domain\Repository\StockRepository;
use App\Domain\Repository\CouponRepository;
use App\Domain\Repository\PaymentRepository;
use App\Domain\Repository\ProductRepository;
use Symfony\Component\Serializer\Serializer;
use App\Domain\Repository\CampaignRepository;
use App\Domain\Repository\OrderItemRepository;
use App\Infrastructure\Serializer\UserNormalize;
use App\Infrastructure\Serializer\OrderNormalize;
use App\Infrastructure\Serializer\StockNormalize;
use App\Infrastructure\Serializer\ProductNormalize;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use App\Infrastructure\Serializer\OrderItemNormalize;
use Symfony\Component\Serializer\SerializerInterface;
use App\Infrastructure\Repository\Eloquent\DBUserRepository;
use App\Infrastructure\Repository\Eloquent\DBOrderRepository;
use App\Infrastructure\Repository\Eloquent\DBStockRepository;
use App\Infrastructure\Framework\Database\EloquentTransaction;
use App\Infrastructure\Repository\Eloquent\DBCouponRepository;
use App\Infrastructure\Repository\Eloquent\DBProductRepository;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use App\Infrastructure\Repository\Eloquent\DBCampaignRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Infrastructure\Repository\Eloquent\DBOrderItemRepository;
use Symfony\Component\Serializer\Normalizer\DenormalizableInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use App\Infrastructure\Repository\PaymentRepository as PaymentRepositoryInfra;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;

class AppServiceProvider extends ServiceProvider
{
    public $binding = [
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Transaction::class, EloquentTransaction::class);
        $this->app->bind(CampaignRepository::class, DBCampaignRepository::class);
        $this->app->bind(CouponRepository::class, DBCouponRepository::class);
        $this->app->bind(OrderRepository::class, DBOrderRepository::class);
        $this->app->bind(StockRepository::class, DBStockRepository::class);
        $this->app->bind(UserRepository::class, DBUserRepository::class);
        $this->app->bind(ProductRepository::class, DBProductRepository::class);
        $this->app->bind(OrderItemRepository::class, DBOrderItemRepository::class);
        $this->app->bind(PaymentRepository::class, PaymentRepositoryInfra::class);
        $this->app->bind(NormalizerInterface::class, SerializerInterface::class);
        $this->app->bind(DenormalizableInterface::class, SerializerInterface::class);

        $this->app->singleton(ClassMetadataFactoryInterface::class, static function ($app) {
            return new ClassMetadataFactory(new YamlFileLoader($app->basePath().'/config/serialization.yaml'));
        });

        $this->app->tag(
            [
                ProductNormalize::class,
                UserNormalize::class,
                OrderNormalize::class,
                OrderItemNormalize::class,
                StockNormalize::class,
            ],
            ['normalizer']
        );

        $this->app->tag(
            [JsonEncode::class],
            ['encoder']
        );

        $this->app->singleton(SerializerInterface::class, static function ($app) {
            return new Serializer(
                iterator_to_array($app->tagged('normalizer')),
                iterator_to_array($app->tagged('encoder'))
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
