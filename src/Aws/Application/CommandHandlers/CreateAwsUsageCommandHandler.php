<?php

namespace Aws\Application\CommandHandlers;

use Aws\Application\Commands\CreateAwsUsageCommand;
use Aws\Domain\Entities\AwsUsageEntity;
use Aws\Domain\Services\CreateAwsUsageService;
use Monolog\Logger;
use Shared\Infrastructure\CommandBus\Dispatcher;

class CreateAwsUsageCommandHandler
{
    public function __construct(private Dispatcher $dispatcher, private CreateAwsUsageService $service)
    {

    }

    public function handle (CreateAwsUsageCommand $cmd): void
    {
        $tag = "";
        switch ($cmd->usage->type->value) {
            case 0:
                $tag = "token";
                break;
            case 1:
                $tag = "image";
                break;
            case 2:
                $tag = "audio";
                break;
        }

        $customer_id = $cmd->user->getAws()->getCustomerId();
        $plan = $cmd->user->getActiveSubscription()->getPlan();
        $allocatedAudio = $plan->getAudioCredit()->value;
        $allocatedImages = $plan->getImageCredit()->value;
        $allocatedToken = $plan->getTokenCredit()->value;
        $dimension = $cmd->user->getActiveSubscription()->getPlan()->getTitle()->value;
        $quantity = $cmd->usage->value;
        $this->service->add(new AwsUsageEntity($customer_id, $dimension, $allocatedAudio, $allocatedToken, $allocatedImages, $tag, $quantity));
    }
}