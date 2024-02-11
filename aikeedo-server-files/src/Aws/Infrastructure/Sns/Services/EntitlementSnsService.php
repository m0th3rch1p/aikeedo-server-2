<?php

namespace Aws\Infrastructure\Sns\Services;

use Aws\Domain\Repositories\SnsServiceInterface;

class EntitlementSnsService implements SnsServiceInterface
{
//    private string $endpoint = "/api/aws/ent/webhook";
    private string $endpoint = "arn:aws:sqs:us-east-1:436917423698:chatrov2";

    private string $protocol = "sqs";
    private string $topicArn = "arn:aws:sns:us-east-1:287250355862:aws-mp-entitlement-notification-1cothn9ewdy8kts24xi9fre3y";

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }


    public function getProtocol(): string
    {
        return $this->protocol;
    }

    public function getTopicArn(): string
    {
        return $this->topicArn;
    }
}