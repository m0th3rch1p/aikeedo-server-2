<?php

namespace Aws\Infrastructure\Sns\Services;

use Aws\Credentials\Credentials;
use Aws\Domain\Repositories\SnsServiceInterface;
use Aws\Sns\SnsClient;

class SubscriptionSnsService implements SnsServiceInterface
{
    private string $protocol = "https";
    private string $endpoint = "/api/aws/subscription/webhook";
    private string $topicArn = "arn:aws:sns:us-east-1:287250355862:aws-mp-subscription-notification-1cothn9ewdy8kts24xi9fre3y";

    public function getEndpoint(): string
    {
        // TODO: Implement getEndpoint() method.
        return $this->endpoint;
    }

    public function getProtocol(): string
    {
        // TODO: Implement getProtocol() method.
        return $this->protocol;
    }

    public function getTopicArn(): string
    {
        // TODO: Implement getTopicArn() method.
        return $this->topicArn;
    }
}