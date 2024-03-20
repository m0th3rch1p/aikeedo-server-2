<?php

declare(strict_types=1);

namespace Awssubscription\Application\CommandHandlers;

use Awssubscription\Application\Commands\UpdateAwssubscriptionCommand;
use Awssubscription\Domain\Entities\AwssubscriptionEntity;
use Awssubscription\Domain\Exceptions\AwssubscriptionNotFoundException;
use Awssubscription\Domain\Services\UpdateAwssubscriptionService;

/**
 * @package Awssubscription\Application\CommandHandlers
 */
class UpdateAwssubscriptionCommandHandler
{
    /**
     * @param UpdateAwssubscriptionService $service
     * @return void
     */
    public function __construct(
        private UpdateAwssubscriptionService $service,
    ) {
    }

    /**
     * @param UpdateAwssubscriptionCommand $cmd
     * @return AwssubscriptionEntity
     * @throws AwssubscriptionNotFoundException
     */
    public function handle(UpdateAwssubscriptionCommand $cmd): AwssubscriptionEntity
    {
        $awssubscription = $this->service->findAwssubscriptionOrFail($cmd->id);
        $this->service->updateAwssubscription($awssubscription);
        return $awssubscription;
    }
}
