<?php
/**
 * Notes repository.
 */

namespace App\Repository;

use App\Entity\Notes;
use App\Entity\User;
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
    const PAGINATOR_ITEMS_PER_PAGE = 10;

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
     * @param array $filters Filters array
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(array $filters = []): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial notes.{id, createdAt, title, description}',
                'partial categories.{id, name}'
            )
            ->join('notes.categories', 'categories')
            ->orderBy('notes.createdAt', 'DESC');
        $queryBuilder = $this->applyFiltersToList($queryBuilder, $filters);

        return $queryBuilder;
    }


    /**
     * Query tasks by author.
     *
     * @param \App\Entity\User $user    User entity
     * @param array            $filters Filters array
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryByAuthor(User $user, array $filters = []): QueryBuilder
    {
        $queryBuilder = $this->queryAll($filters);
        $queryBuilder->andWhere('notes.author = :author')
            ->setParameter('author', $user);

        return $queryBuilder;
    }

    /**
     * Prepare filters for the tasks list.
     *
     * @param array $filters Raw filters from request
     *
     * @return array Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (isset($filters['categories_id']) && is_numeric($filters['categories_id'])) {
            $categories = $this->categoriesService->findOneById(
                $filters['categories_id']
            );
            if (null !== $categories) {
                $resultFilters['categories'] = $categories;
            }
        }

        return $resultFilters;
    }

    /**
     * Apply filters to paginated list.
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder
     * @param array                      $filters      Filters array
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['categories']) && $filters['categories'] instanceof Categories) {
            $queryBuilder->andWhere('categories = :categories')
                ->setParameter('categories', $filters['categories']);
        }

        return $queryBuilder;
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
