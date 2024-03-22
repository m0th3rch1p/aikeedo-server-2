<?php

declare(strict_types=1);

namespace Awssubscription\Application\Commands;

use Awssubscription\Application\CommandHandlers\ReadAwssubscriptionCommandHandler;
use Shared\Domain\ValueObjects\Id;
use Shared\Infrastructure\CommandBus\Attributes\Handler;

/**
 * @package Awssubscription\Application\Commands
 */
#[Handler(ReadAwssubscriptionCommandHandler::class)]
class ReadAwssubscriptionCommand
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
