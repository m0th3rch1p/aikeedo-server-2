<?php

declare(strict_types=1);

namespace Aws\Infrastructure;

use Application;
use Aws\Credentials\Credentials;
use Aws\Domain\Repositories\AwsRepositoryInterface;
use Aws\Domain\Repositories\AwsUsageRepositoryInterface;
use Aws\Infrastructure\Repositories\DoctrineOrm\AwsRepository;
use Aws\Infrastructure\Repositories\DoctrineOrm\AwsUsageRepository;
use Aws\Infrastructure\Services\EntitlementService;
use Aws\Infrastructure\Sns\Services\EntitlementSnsService;
use Aws\Infrastructure\Sns\Services\SubscriptionSnsService;
use Aws\Infrastructure\Sns\SnsFactory;
use Aws\Sns\SnsClient;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Shared\Infrastructure\BootstrapperInterface;

/**
 * @package Aws\Infrastructure
 */
class AwsModuleBootstrapper implements BootstrapperInterface
{
    /**
     * @param Application $app
     * @return void
     */
    public function __construct(
        private Application $app,
        private SnsFactory $factory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        // Register repository implementations
        $this->app->set(
            AwsRepositoryInterface::class,
            AwsRepository::class
        );

        $this->app->set(AwsUsageRepositoryInterface::class, AwsUsageRepository::class);
        try {
            $this->resolveSnsServices();
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            die($e);
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function resolveSnsServices (): void
    {
        $this->factory
            ->register(SubscriptionSnsService::class)
            ->register(EntitlementSnsService::class);
//            ->resolveServices();
    }
}
