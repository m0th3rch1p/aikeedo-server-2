<?php

declare(strict_types=1);

namespace Awssubscription\Domain\Events;

use Awssubscription\Domain\Entities\AwssubscriptionEntity;

/**
 * @package Awssubscription\Domain\Events
 */
abstract class AbstractAwssubscriptionEvent
{
    /**
     * @param AwssubscriptionEntity $awssubscription
     * @return void
     */
    public function __construct(
        public readonly AwssubscriptionEntity $awssubscription,
    ) {
    }
}
