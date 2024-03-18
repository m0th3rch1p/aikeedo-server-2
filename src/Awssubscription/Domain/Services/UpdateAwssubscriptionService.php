<?php

declare(strict_types=1);

namespace Awssubscription\Domain\Services;

use Awssubscription\Domain\Entities\AwssubscriptionEntity;
use Awssubscription\Domain\Events\AwssubscriptionUpdatedEvent;
use Awssubscription\Domain\Repositories\AwssubscriptionRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @package Awssubscription\Domain\Services
 */
class UpdateAwssubscriptionService extends ReadAwssubscriptionService
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
        parent::__construct($repo);
    }

    /**
     * @param AwssubscriptionEntity $awssubscription
     * @return void
     */
    public function updateAwssubscription(AwssubscriptionEntity $awssubscription)
    {
        // Call the pre update hooks
        $awssubscription->preUpdate();

        // Update the awssubscription in the repository
        $this->repo->flush();

        // Dispatch the awssubscription updated event
        $event = new AwssubscriptionUpdatedEvent($awssubscription);
        $this->dispatcher->dispatch($event);
    }
}
