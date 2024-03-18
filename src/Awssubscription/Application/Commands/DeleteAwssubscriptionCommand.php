<?php

declare(strict_types=1);

namespace Awssubscription\Application\Commands;

use Awssubscription\Application\CommandHandlers\DeleteAwssubscriptionCommandHandler;
use Shared\Domain\ValueObjects\Id;
use Shared\Infrastructure\CommandBus\Attributes\Handler;

/**
 * @package Awssubscription\Application\Commands
 */
#[Handler(DeleteAwssubscriptionCommandHandler::class)]
class DeleteAwssubscriptionCommand
{
    public Id $id;

    /**
     * @param string $id
     * @return void
     */
    public function __construct(string $id)
    {
        $this->id = new Id($id);
    }
}
