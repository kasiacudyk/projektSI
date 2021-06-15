<?php
/**
 * Notes repository.
 */

namespace App\Repository;

use App\Entity\Notes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Notes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notes[]    findAll()
 * @method Notes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotesRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 5;

    /**
     * NotesRepository constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notes::class);
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Notes $notes Notes entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Notes $notes): void
    {
        $this->_em->persist($notes);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Notes $notes Notes entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Notes $notes): void
    {
        $this->_em->remove($notes);
        $this->_em->flush();
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('notes.createdAt', 'DESC');
    }

    /**
     * Get or create new query builder.
     *
     * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder Query builder
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('notes');
    }
}
