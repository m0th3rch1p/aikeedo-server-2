<?php

declare(strict_types=1);

namespace Awssubscription\Domain\Repositories;

use Awssubscription\Domain\Entities\AwssubscriptionEntity;
use Awssubscription\Domain\ValueObjects\SortParameter;
use Iterator;
use Shared\Domain\Repositories\RepositoryInterface;
use Shared\Domain\ValueObjects\Id;
use Shared\Domain\ValueObjects\SortDirection;

/**
 * @package Awssubscription\Domain\Repositories
 */
interface AwssubscriptionRepositoryInterface extends RepositoryInterface
{
    /**
     * Add new entityt to the repository
     *
     * @param AwssubscriptionEntity $awssubscription
     * @return AwssubscriptionRepositoryInterface
     */
    public function add(AwssubscriptionEntity $awssubscription): self;

    /**
     * Remove the entity from the repository
     *
     * @param AwssubscriptionEntity $awssubscription
     * @return AwssubscriptionRepositoryInterface
     */
    public function remove(AwssubscriptionEntity $awssubscription): self;

    /**
     * Find entity by id
     *
     * @param Id $id
     * @return null|AwssubscriptionEntity
     */
    public function ofId(Id $id): ?AwssubscriptionEntity;

    /**
     * @param SortDirection $dir
     * @param null|SortParameter $sortParameter
     * @return static
     */
    public function sort(SortDirection $dir, ?SortParameter $sortParameter = null): static;

    /**
     * @param AwssubscriptionEntity $cursor
     * @return Iterator<AwssubscriptionEntity>
     */
    public function startingAfter(AwssubscriptionEntity $cursor): Iterator;

    /**
     * @param AwssubscriptionEntity $cursor
     * @return Iterator<AwssubscriptionEntity>
     */
    public function endingBefore(AwssubscriptionEntity $cursor): Iterator;
}
