<?php

declare(strict_types=1);

namespace Awssubscription\Domain\Services;

use Awssubscription\Domain\Entities\AwssubscriptionEntity;
use Awssubscription\Domain\Exceptions\AwssubscriptionNotFoundException;
use Awssubscription\Domain\Repositories\AwssubscriptionRepositoryInterface;
use Shared\Domain\ValueObjects\Id;

/**
 * @package Awssubscription\Domain\Services
 */
class ReadAwssubscriptionService
{
    /**
     * @param AwssubscriptionRepositoryInterface $repo
     * @return void
     */
    public function __construct(
        private AwssubscriptionRepositoryInterface $repo,
    ) {
    }

    /**
     * @param Id $id
     * @return AwssubscriptionEntity
     * @throws AwssubscriptionNotFoundException
     */
    public function findAwssubscriptionOrFail(Id $id)
    {
        $awssubscription = $this->repo->ofId($id);
        if (null === $awssubscription) {
            throw new AwssubscriptionNotFoundException($id);
        }

        return $awssubscription;
    }
}
