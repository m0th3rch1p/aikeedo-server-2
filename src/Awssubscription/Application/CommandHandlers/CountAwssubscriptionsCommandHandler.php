<?php

declare(strict_types=1);

namespace Awssubscription\Application\CommandHandlers;

use Awssubscription\Application\Commands\CountAwssubscriptionsCommand;
use Awssubscription\Domain\Repositories\AwssubscriptionRepositoryInterface;

/**
 * @package Awssubscription\Application\CommandHandlers
 */
class CountAwssubscriptionsCommandHandler
{
    /**
     * @param AwssubscriptionRepositoryInterface $repo
     * @return void
     */
    public function __construct(
        private AwssubscriptionRepositoryInterface $repo,
    ) {
    }

    /**
     * @param CountAwssubscriptionsCommand $cmd
     * @return int
     */
    public function handle(CountAwssubscriptionsCommand $cmd): int
    {
        $awssubscriptions = $this->repo;

        return $awssubscriptions->count();
    }
}
