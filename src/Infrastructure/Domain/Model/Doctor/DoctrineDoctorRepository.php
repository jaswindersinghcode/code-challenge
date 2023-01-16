<?php

namespace CodeChallenge\Infrastructure\Domain\Model\Doctor;

use CodeChallenge\Domain\Model\Doctor\Doctor;
use CodeChallenge\Domain\Model\Doctor\DoctorRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineDoctorRepository extends ServiceEntityRepository implements DoctorRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Doctor::class);
        $this->entityManager = $entityManager;
    }
    public function findById($id): ?Doctor
    {
        return $this->findOneBy(['id'=>$id]);
    }
    public function save(Doctor $doctor): void
    {
        $this->getEntityManager()->persist($doctor);
        $this->getEntityManager()->flush();
    }
    public function all(): array
    {
        // TODO: Implement all() method.
    }
}