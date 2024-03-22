<?php

declare(strict_types=1);

namespace Awssubscription\Application\Commands;

use Awssubscription\Application\CommandHandlers\CountAwssubscriptionsCommandHandler;
use Shared\Infrastructure\CommandBus\Attributes\Handler;

/**
 * @package Awssubscription\Application\Commands
 */
#[Handler(CountAwssubscriptionsCommandHandler::class)]
class CountAwssubscriptionsCommand
{
}
