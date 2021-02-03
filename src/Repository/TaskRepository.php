<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
  */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @param Task $task
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function save(Task $task): Task
    {
        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();

        return $task;
    }

    /**
     * @param Task $task
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(Task $task): void
    {
        $this->getEntityManager()->remove($task);
        $this->getEntityManager()->flush();
    }

    /**
     * @return Task[] Returns an array of Task objects
     */
    public function findByProjectId($id): array
    {
        return $this->createQueryBuilder('t')
                    ->andWhere('t.project = :project')
                    ->setParameter('project', $id)
                    ->orderBy('t.id', 'ASC')
                    ->setMaxResults(10)
                    ->getQuery()
                    ->getResult();
    }
}
