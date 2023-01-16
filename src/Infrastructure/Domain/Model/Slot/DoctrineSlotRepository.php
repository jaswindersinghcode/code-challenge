<?php

namespace CodeChallenge\Infrastructure\Domain\Model\Slot;

use CodeChallenge\Domain\Model\Slot\Criteria;
use CodeChallenge\Domain\Model\Slot\Slot;
use CodeChallenge\Domain\Model\Slot\SlotRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DoctrineSlotRepository extends ServiceEntityRepository implements SlotRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Slot::class);
        $this->entityManager = $entityManager;
    }

    public function findByCriteria(Criteria $criteria): ?Slot
    {
        return null;
    }

    public function save(Slot $slot): void
    {
        $this->getEntityManager()->persist($slot);
        $this->getEntityManager()->flush();
    }

    public function all(): array
    {
        // TODO: Implement all() method.
    }

    public function allSlotsByDoctor($id): ?array
    {
        // TODO: Implement allSlotsByDoctor() method.
    }
}