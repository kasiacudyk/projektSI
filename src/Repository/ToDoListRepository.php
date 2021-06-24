<?php
/**
 * ToDoList repository.
 */

namespace App\Repository;

use App\Entity\Tags;
use App\Entity\ToDoList;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ToDoList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToDoList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToDoList[]    findAll()
 * @method ToDoList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToDoListRepository extends ServiceEntityRepository
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
     * ToDoListRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToDoList::class);
    }

    /**
     * Save record.
     *
     * @param \App\Entity\ToDoList $todolist ToDoList entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(ToDoList $todolist): void
    {
        $this->_em->persist($todolist);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\ToDoList $todolist ToDoList entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(ToDoList $todolist): void
    {
        $this->_em->remove($todolist);
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
                'partial todolist.{id, title}',
                'partial tags.{id, name}'
            )
            ->join('todolist.tags', 'tags')
            ->orderBy('todolist.title', 'ASC');
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
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('todolist.author = :author')
            ->setParameter('author', $user);

        return $queryBuilder;
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
        if (isset($filters['tags']) && $filters['tags'] instanceof Tags) {
            $queryBuilder->andWhere('tags IN (:tags)')
                ->setParameter('tags', $filters['tags']);
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
        return $queryBuilder ?? $this->createQueryBuilder('todolist');
    }
}
