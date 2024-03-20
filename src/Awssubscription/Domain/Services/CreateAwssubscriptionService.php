<?php

declare(strict_types=1);

namespace Awssubscription\Domain\Services;

use Awssubscription\Domain\Entities\AwssubscriptionEntity;
use Awssubscription\Domain\Events\AwssubscriptionCreatedEvent;
use Awssubscription\Domain\Repositories\AwssubscriptionRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @package Awssubscription\Domain\Services
 */
class CreateAwssubscriptionService
{
    /**
     * @param AwssubscriptionRepositoryInterface $repo
     * @param EventDispatcherInterface $dispatcher
     * @return void
     */
    public function __construct(
        private AwssubscriptionRepositoryInterface $repo,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    /**
     * @param AwssubscriptionEntity $awssubscription
     * @return void
     */
    public function createAwssubscription(AwssubscriptionEntity $awssubscription)
    {
        // Add the awssubscription to the repository
        $this->repo
            ->add($awssubscription)
            ->flush();

        // Dispatch the awssubscription created event
        $event = new AwssubscriptionCreatedEvent($awssubscription);
        $this->dispatcher->dispatch($event);
    }
}
