<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Enum\BudgetStatus;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\BudgetRepository;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation\Timestampable;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use App\Validator\Constraints\UniqueMonthBudget;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Put(
            denormalizationContext: ['groups' => ['budget:put']]
        ),
        new Delete(),
        new GetCollection(),
        new Post(),
    ],
    normalizationContext: ['groups' => ['budget:read']],
    denormalizationContext: ['groups' => ['budget:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'status' => 'exact', 'date' => 'partial'])]
#[ApiFilter(OrderFilter::class, properties: ['createdAt' => 'DESC'])]
class Budget
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[Groups('budget:read')]
    private ?Uuid $id;

    #[ORM\Column(length: 255)]
    #[ApiProperty(example: 'Budget Janvier 2023')]
    #[Groups(['budget:read', 'budget:write', 'budget:put'])]
    private string $title;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[UniqueMonthBudget]
    #[Groups(['budget:read', 'budget:write', 'budget:put'])]
    private \DateTime $date;

    #[ORM\Column(type: Types::STRING, length: 255, enumType: BudgetStatus::class)]
    #[ApiProperty(example: 'Draft')]
    #[Groups(['budget:read', 'budget:put'])]
    private BudgetStatus $status;

    //TODO: Add inherent view of transactions in budget
    #[ORM\OneToMany(mappedBy: 'budget', targetEntity: Transaction::class, orphanRemoval: true)]
    #[Groups(['budget:read', 'budget:put'])]
    private Collection $transactions;

    #[ORM\OneToOne(targetEntity: BankExtraction::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[ApiProperty(types: ['https://schema.org/image'])]
    private ?BankExtraction $extraction = null;

    /**
     * @Timestampable(on="create")
     */
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups('budget:read')]
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->id = $id ?? Uuid::v6();
        $this->transactions = new ArrayCollection();
        $this->status = BudgetStatus::ToBeDone;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    #[Groups('budget:read')]
    public function getFormatedDate(): string
    {
        return $this->date->format('m/y');
    }

    public function setDate(?\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus(): BudgetStatus
    {
        return $this->status;
    }

    public function setStatus(?BudgetStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setBudget($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            if ($transaction->getBudget() === $this) {
                $transaction->setBudget(null);
            }
        }

        return $this;
    }

    #[Groups('budget:read')]
    public function getTransactionsTotal(): int
    {
        $total = 0;
        /** @var Transaction $transaction */
        foreach ($this->transactions as $transaction) {
            $total += $transaction->getAmount();
        }

        return $total;
    }

    //TODO : Add Somme transactions Crédit 
    //TODO : Add Somme Transactions Débit
    //TODO : Add Différence Crédit - Débit
    //TODO : Add percent of diff


    #[Groups('budget:read')]
    public function getTransactionsMedium(): int
    {
        $medium = 0;
        /** @var Transaction $transaction */
        foreach ($this->transactions as $transaction) {
            $medium += $transaction->getAmount() / $this->transactions->count();
        }

        return $medium;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getExtraction()
    {
        return $this->extraction;
    }

    public function setExtraction($extraction)
    {
        $this->extraction = $extraction;

        return $this;
    }
}
