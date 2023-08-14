<?php

namespace App\Repository;

use App\Entity\BankTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BankTranslation>
 *
 * @method BankTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method BankTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method BankTranslation[]    findAll()
 * @method BankTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BankTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BankTranslation::class);
    }

    public function save(BankTranslation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BankTranslation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function isLabelTranslated(string $label): ?BankTranslation
    {
        $expr = $this->_em->getExpressionBuilder();
        $translation = $this
            ->createQueryBuilder('bt')
            ->select('bt')
            ->where($expr->in('bt.bankLabel', ':label'))
            ->setParameter(':label', $label)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return $translation;
    }
}
