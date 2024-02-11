<?php
declare(strict_types=1);

namespace Presentation\Resources;

use Aws\Domain\Entities\AwsEntity;

class AwsResource
{
    public readonly string $customer_id;
    public readonly string $dimension;

    /**
     * @param string $customer_id
     * @param string $dimension
     */
    public function __construct(AwsEntity $awsEntity)
    {
        $this->customer_id = $awsEntity->getCustomerId();
        $this->dimension = $awsEntity->getDimension();
    }


}