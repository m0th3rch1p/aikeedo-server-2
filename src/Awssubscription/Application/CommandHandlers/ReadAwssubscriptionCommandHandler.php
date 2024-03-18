<?php

declare(strict_types=1);

namespace Awssubscription\Application\CommandHandlers;

use Awssubscription\Application\Commands\ReadAwssubscriptionCommand;
use Awssubscription\Domain\Entities\AwssubscriptionEntity;
use Awssubscription\Domain\Exceptions\AwssubscriptionNotFoundException;
use Awssubscription\Domain\Services\ReadAwssubscriptionService;

/**
 * @package Awssubscription\Application\CommandHandlers
 */
class ReadAwssubscriptionCommandHandler
{
    /**
     * @param ReadAwssubscriptionService $service
     * @return void
     */
    public function __construct(
        private ReadAwssubscriptionService $service,
    ) {
    }

    /**
     * @param ReadAwssubscriptionCommand $cmd
     * @return AwssubscriptionEntity
     * @throws AwssubscriptionNotFoundException
     */
    public function handle(ReadAwssubscriptionCommand $cmd): AwssubscriptionEntity
    {
        return $this->service->findAwssubscriptionOrFail($cmd->id);
    }
}
