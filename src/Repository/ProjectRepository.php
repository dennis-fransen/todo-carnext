<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @param Project $project
     *
     * @return Project
     * @throws \Doctrine\ORM\ORMException
     */
    public function save(Project $project): Project
    {
        $this->getEntityManager()->persist($project);
        $this->getEntityManager()->flush();

        return $project;
    }

    /**
     * @param Project $project
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(Project $project): void
    {
        $this->getEntityManager()->remove($project);
        $this->getEntityManager()->flush();
    }

}
