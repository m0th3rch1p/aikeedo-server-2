<?php

declare(strict_types=1);

namespace Aws\Application\Commands;

use Aws\Application\CommandHandlers\CreateAwsCommandHandler;
use Shared\Domain\ValueObjects\StringValue;
use Shared\Infrastructure\CommandBus\Attributes\Handler;

/**
 * @package Aws\Application\Commands
 */
#[Handler(CreateAwsCommandHandler::class)]
class CreateAwsCommand
{
    public string $customerId;

    /**
     * @param string $customerId
     */
    public function __construct(string $customerId)
    {
        $this->customerId = $customerId;
    }
}
