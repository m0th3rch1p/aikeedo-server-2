<?php

declare(strict_types=1);

namespace Awssubscription\Domain\Entities;

use Aws\Domain\Entities\AwsEntity;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\ValueObjects\Id;

/**
 * @package Awssubscription\Domain\Entities
 */
#[ORM\Entity]
#[ORM\Table(name: 'awssubscription')]
class AwssubscriptionEntity
{
    /**
     * A unique numeric identifier of the entity. Don't set this property
     * programmatically. It is automatically set by Doctrine ORM.
     */
    #[ORM\Embedded(class: Id::class, columnPrefix: false)]
    private Id $id;

    #[ORM\Column(name: "dimension", type: Types::STRING)]
    private string $dimension;

    #[ORM\Column(name: "quantity", type: Types::INTEGER)]
    private int $quantity;

    #[ORM\ManyToOne(targetEntity: AwsEntity::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'aws_id')]
    private AwsEntity $aws;

    /** Creation date and time of the entity */
    #[ORM\Column(type: 'datetime', name: 'created_at')]
    private DateTimeInterface $createdAt;

    /** The date and time when the entity was last modified. */
    #[ORM\Column(type: 'datetime', name: 'updated_at', nullable: true)]
    private ?DateTimeInterface $updatedAt = null;

    /**
     * @return void
     */
    public function __construct(string $dimension, int $quantity, AwsEntity $aws)
    {
        $this->id = new Id();
        $this->dimension = $dimension;
        $this->quantity = $quantity;
        $this->aws = $aws;
        $this->createdAt = new DateTime();
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return null|DateTimeInterface
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @return void
     */
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function getDimension(): string
    {
        return $this->dimension;
    }

    public function setDimension(string $dimension): void
    {
        $this->dimension = $dimension;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getAws(): AwsEntity
    {
        return $this->aws;
    }

    public function setAws(AwsEntity $aws): void
    {
        $this->aws = $aws;
    }
}
