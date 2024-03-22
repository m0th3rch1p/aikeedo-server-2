<?php

declare(strict_types=1);

namespace Awssubscription\Application\CommandHandlers;

use Awssubscription\Application\Commands\DeleteAwssubscriptionCommand;
use Awssubscription\Domain\Exceptions\AwssubscriptionNotFoundException;
use Awssubscription\Domain\Services\DeleteAwssubscriptionService;

/**
 * @package Awssubscription\Application\CommandHandlers
 */
class DeleteAwssubscriptionCommandHandler
{
    /**
     * @param DeleteAwssubscriptionService $service
     * @return void
     */
    public function __construct(
        private DeleteAwssubscriptionService $service,
    ) {
    }

    /**
     * @param DeleteAwssubscriptionCommand $cmd
     * @return void
     * @throws AwssubscriptionNotFoundException
     */
    public function handle(DeleteAwssubscriptionCommand $cmd): void
    {
        $awssubscription = $this->service->findAwssubscriptionOrFail($cmd->id);
        $this->service->deleteAwssubscription($awssubscription);
    }
}
