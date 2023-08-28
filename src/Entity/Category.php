<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Put(),
        new Delete(),
        new GetCollection(),
        new Post(),
    ],
    normalizationContext: ['groups' => ['category:read']],
    denormalizationContext: ['groups' => ['category:write']]
)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[Groups('transaction:read', 'category:read')]
    private ?Uuid $id;

    #[ORM\Column(length: 255)]
    #[Groups(['transaction:read', 'category:read', 'domain:read', 'category:write'])]
    private ?string $label = null;

    #[ORM\ManyToOne(inversedBy: 'categories')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['transaction:read', 'category:read', 'category:write'])]
    private ?Domain $domain = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Transaction::class, orphanRemoval: false)]
    #[Groups(['category:read', 'domain:read'])]
    private Collection $transactions;

    public function __construct()
    {
        $this->id = $id ?? Uuid::v6();
        $this->transactions = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDomain(): ?Domain
    {
        return $this->domain;
    }

    public function setDomain(?Domain $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    #[Groups('category:read')]
    public function getTransactionsTotal(): int
    {
        $total = 0;
        /** @var Transaction $transaction */
        foreach ($this->transactions as $transaction) {
            $total += $transaction->getAmount();
        }

        return $total;
    }


    #[Groups('category:read')]
    public function getTransactionsMedium(): int
    {
        $medium = 0;
        /** @var Transaction $transaction */
        foreach ($this->transactions as $transaction) {
            $medium += $transaction->getAmount() / $this->transactions->count();
        }

        return $medium;
    }

    public function setTransactions(Collection $transactions): void
    {
        $this->transactions = $transactions;
    }
}
