<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use Gedmo\Mapping\Annotation\Blameable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BankExtractionRepository::class)]
#[ApiResource(
    operations: [
        new Put(),
        new Delete(),
        new GetCollection(),
        new Post(),
    ],
)]
class BankTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id;

    #[ORM\Column(length: 255)]
    private ?string $bankLabel = null;

    #[ORM\Column(length: 255)]
    private ?string $customLabel = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Blameable(on: 'create')]
    private ?User $createdBy;

    public function __construct()
    {
        $this->id = $id ?? Uuid::v6();
    }

    public function getId(): ?Uuid
    {
        return $this->id = $id ?? Uuid::v6();
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    public function getCustomLabel()
    {
        return $this->customLabel;
    }

    public function setCustomLabel($customLabel)
    {
        $this->customLabel = $customLabel;

        return $this;
    }

    public function getBankLabel()
    {
        return $this->bankLabel;
    }

    public function setBankLabel($bankLabel)
    {
        $this->bankLabel = $bankLabel;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }
}
