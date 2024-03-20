<?php

declare(strict_types=1);

namespace Awssubscription\Application\CommandHandlers;

use Awssubscription\Application\Commands\ListAwssubscriptionsCommand;
use Awssubscription\Domain\Entities\AwssubscriptionEntity;
use Awssubscription\Domain\Exceptions\AwssubscriptionNotFoundException;
use Awssubscription\Domain\Repositories\AwssubscriptionRepositoryInterface;
use Awssubscription\Domain\Services\ReadAwssubscriptionService;
use Shared\Domain\ValueObjects\CursorDirection;
use Traversable;

/**
 * @package Awssubscription\Application\CommandHandlers
 */
class ListAwssubscriptionsCommandHandler
{
    /**
     * @param AwssubscriptionRepositoryInterface $repo
     * @param ReadAwssubscriptionService $service
     * @return void
     */
    public function __construct(
        private AwssubscriptionRepositoryInterface $repo,
        private ReadAwssubscriptionService $service,
    ) {
    }

    /**
     * @param ListAwssubscriptionsCommand $cmd
     * @return Traversable<AwssubscriptionEntity>
     * @throws AwssubscriptionNotFoundException
     */
    public function handle(ListAwssubscriptionsCommand $cmd): Traversable
    {
        $cursor = $cmd->cursor
            ? $this->service->findAwssubscriptionOrFail($cmd->cursor)
            : null;

        $awssubscriptions = $this->repo
            ->sort($cmd->sortDirection, $cmd->sortParameter);

        if ($cmd->maxResults) {
            $awssubscriptions = $awssubscriptions->setMaxResults($cmd->maxResults);
        }

        if ($cursor) {
            if ($cmd->cursorDirection == CursorDirection::ENDING_BEFORE) {
                return $awssubscriptions = $awssubscriptions->endingBefore($cursor);
            }

            return $awssubscriptions->startingAfter($cursor);
        }

        return $awssubscriptions;
    }
}
