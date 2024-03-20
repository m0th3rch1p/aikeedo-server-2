<?php

declare(strict_types=1);

namespace Awssubscription\Infrastructure;

use Application;
use Awssubscription\Domain\Repositories\AwssubscriptionRepositoryInterface;
use Awssubscription\Infrastructure\Repositories\DoctrineOrm\AwssubscriptionRepository;
use Shared\Infrastructure\BootstrapperInterface;

/**
 * @package Awssubscription\Infrastructure
 */
class AwssubscriptionModuleBootstrapper implements BootstrapperInterface
{
    /**
     * @param Application $app
     * @return void
     */
    public function __construct(
        private Application $app,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        // Register repository implementations
        $this->app->set(
            AwssubscriptionRepositoryInterface::class,
            AwssubscriptionRepository::class
        );
    }
}
