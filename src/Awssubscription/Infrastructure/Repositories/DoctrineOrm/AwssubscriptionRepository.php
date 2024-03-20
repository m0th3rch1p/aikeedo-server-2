<?php

declare(strict_types=1);

namespace Awssubscription\Infrastructure\Repositories\DoctrineOrm;

use Awssubscription\Domain\Entities\AwssubscriptionEntity;
use Awssubscription\Domain\Repositories\AwssubscriptionRepositoryInterface;
use Awssubscription\Domain\ValueObjects\SortParameter;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Iterator;
use RuntimeException;
use Shared\Domain\ValueObjects\Id;
use Shared\Domain\ValueObjects\SortDirection;
use Shared\Infrastructure\Repositories\DoctrineOrm\AbstractRepository;

/**
 * @package Awssubscription\Infrastructure\Repositories\DoctrineOrm
 */
class AwssubscriptionRepository extends AbstractRepository implements AwssubscriptionRepositoryInterface
{
    private const ENTITY_CLASS = AwssubscriptionEntity::class;
    private const ALIAS = 'awssubscription';

    private ?SortParameter $sortParameter = null;

    /**
     * @param EntityManagerInterface $em
     * @return void
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @inheritDoc
     */
    public function add(AwssubscriptionEntity $awssubscription): self
    {
        $this->em->persist($awssubscription);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function remove(AwssubscriptionEntity $awssubscription): self
    {
        $this->em->remove($awssubscription);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function ofId(Id $id): ?AwssubscriptionEntity
    {
        $object = $this->em->find(self::ENTITY_CLASS, $id);
        return $object instanceof AwssubscriptionEntity ? $object : null;
    }

    /**
     * @inheritDoc
     */
    public function sort(SortDirection $dir, ?SortParameter $sortParameter = null): static
    {
        $cloned = $this->doSort($dir, $this->getSortKey($sortParameter));
        $cloned->sortParameter = $sortParameter;

        return $cloned;
    }

    /**
     * @inheritDoc
     */
    public function startingAfter(AwssubscriptionEntity $cursor): Iterator
    {
        return $this->doStartingAfter(
            $cursor->getId(),
            $this->getCompareValue($cursor)
        );
    }

    /**
     * @inheritDoc
     */
    public function endingBefore(AwssubscriptionEntity $cursor): Iterator
    {
        return $this->doEndingBefore(
            $cursor->getId(),
            $this->getCompareValue($cursor)
        );
    }

    /**
     * @param null|SortParameter $param
     * @return string
     */
    private function getSortKey(?SortParameter $param): ?string
    {
        return match ($param) {
            SortParameter::ID => 'id.value',
            SortParameter::CREATED_AT => 'createdAt',
            SortParameter::UPDATED_AT => 'updatedAt',
            default => null,
        };
    }

    /**
     * @param AwssubscriptionEntity $cursor
     * @return null|string|DateTimeInterface
     */
    private function getCompareValue(AwssubscriptionEntity $cursor): null|string|DateTimeInterface
    {
        return match ($this->sortParameter) {
            SortParameter::ID => $cursor->getId()->getValue()->getBytes(),
            SortParameter::CREATED_AT => $cursor->getCreatedAt(),
            SortParameter::UPDATED_AT => $cursor->getUpdatedAt(),
            default => null
        };
    }
}
