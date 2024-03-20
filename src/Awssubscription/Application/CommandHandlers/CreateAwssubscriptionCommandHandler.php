<?php

declare(strict_types=1);

namespace Awssubscription\Application\CommandHandlers;

use Awssubscription\Application\Commands\CreateAwssubscriptionCommand;
use Awssubscription\Domain\Entities\AwssubscriptionEntity;
use Awssubscription\Domain\Services\CreateAwssubscriptionService;

/**
 * @package Awssubscription\Application\CommandHandlers
 */
class CreateAwssubscriptionCommandHandler
{
    /**
     * @param CreateAwssubscriptionService $service
     * @return void
     */
    public function __construct(
        private CreateAwssubscriptionService $service,
    ) {
    }

    /**
     * @param CreateAwssubscriptionCommand $cmd
     * @return AwssubscriptionEntity
     */
    public function handle(CreateAwssubscriptionCommand $cmd): AwssubscriptionEntity
    {
        $awssubscription = new AwssubscriptionEntity($cmd->dimension, $cmd->quantity, $cmd->aws);
        $this->service->createAwssubscription($awssubscription);
        return $awssubscription;
    }
}
