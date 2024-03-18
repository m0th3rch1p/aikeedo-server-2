<?php

declare(strict_types=1);

namespace Awssubscription\Domain\Services;

use Awssubscription\Domain\Entities\AwssubscriptionEntity;
use Awssubscription\Domain\Events\AwssubscriptionDeletedEvent;
use Awssubscription\Domain\Repositories\AwssubscriptionRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @package Awssubscription\Domain\Services
 */
class DeleteAwssubscriptionService extends ReadAwssubscriptionService
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
    public function deleteAwssubscription(AwssubscriptionEntity $awssubscription)
    {
        // Delete the awssubscription from the repository
        $this->repo
            ->remove($awssubscription)
            ->flush();

        // Dispatch the awssubscription deleted event
        $event = new AwssubscriptionDeletedEvent($awssubscription);
        $this->dispatcher->dispatch($event);
    }
}
