<?php

namespace App\Entity;

use App\Entity\MediaObject;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\MediaObject\CreateExtractionAction;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BankExtractionRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/budgets/extraction',
            controller: CreateExtractionAction::class,
            validationContext: ['groups' => ['Default', 'extraction:create']], 
        ),
    ],
)]
class BankExtraction
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ApiProperty(identifier: true)]
    private ?Uuid $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(groups: ['extraction:create'])]
    private ?string $month = null;

    #[ORM\OneToOne(targetEntity: MediaObject::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(groups: ['extraction:create'])]
    private MediaObject $mediaObject;

    #[ORM\OneToOne(targetEntity: Budget::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(groups: ['extraction:create'])]
    private Budget $budget;


    public function __construct($id = null)
    {
        $this->id = $id ?? Uuid::v6();
    }

    public function getId(): ?Uuid
    {
        return $this->id = $id ?? Uuid::v6();
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): self
    {
        $this->month = $month;
        return $this;
    }

    public function getMediaObject()
    {
        return $this->mediaObject;
    }

    public function setMediaObject($mediaObject)
    {
        $this->mediaObject = $mediaObject;

        return $this;
    }

    public function getBudget()
    {
        return $this->budget;
    }

    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }
}
