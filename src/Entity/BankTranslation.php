<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\MediaObject;
use ApiPlatform\Metadata\Post;
use App\Controller\MediaObject\CreateExtractionAction;
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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    public function __construct()
    {
        $this->id = $id ?? Uuid::v6();
    }

    public function getId(): ?Uuid
    {
        return $this->id = $id ?? Uuid::v6();
    }

    // ADD GETTER & SETTER
}
