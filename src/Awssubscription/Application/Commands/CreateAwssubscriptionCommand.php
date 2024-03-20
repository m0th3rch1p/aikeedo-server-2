<?php

declare(strict_types=1);

namespace Awssubscription\Application\Commands;

use Aws\Domain\Entities\AwsEntity;
use Awssubscription\Application\CommandHandlers\CreateAwssubscriptionCommandHandler;
use Shared\Infrastructure\CommandBus\Attributes\Handler;

/**
 * @package Awssubscription\Application\Commands
 */
#[Handler(CreateAwssubscriptionCommandHandler::class)]
class CreateAwssubscriptionCommand
{
    public string $dimension;
    public AwsEntity $aws;
    public int $quantity;
    /**
    * @param string $dimension
    * @param string $customerId
    * @param int $quantity
    */
    public function __construct(string $dimension, int $quantity, AwsEntity $aws)
    {
        $this->dimension = $dimension;
        $this->aws = $aws;
        $this->quantity = $quantity;
    }


}
