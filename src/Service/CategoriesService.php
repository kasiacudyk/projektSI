<?php
/**
 * Categories service.
 */

namespace App\Service;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class CategoriesService.
 */
class CategoriesService
{
    /**
     * Categories repository.
     *
     * @var \App\Repository\CategoriesRepository
     */
    private $categoriesRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * CategoriesService constructor.
     *
     * @param \App\Repository\CategoriesRepository      $categoriesRepository Categories repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator          Paginator
     */
    public function __construct(CategoriesRepository $categoriesRepository, PaginatorInterface $paginator)
    {
        $this->categoriesRepository = $categoriesRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->categoriesRepository->queryAll(),
            $page,
            CategoriesRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save categories.
     *
     * @param \App\Entity\Categories $categories Categories entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Categories $categories): void
    {
        $this->categoriesRepository->save($categories);
    }

    /**
     * Delete categories.
     *
     * @param \App\Entity\Categories $categories Categories entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Categories $categories): void
    {
        $this->categoriesRepository->delete($categories);
    }

    /**
     * Find category by Id.
     *
     * @param int $id Categories Id
     *
     * @return \App\Entity\Categories|null Categories entity
     */
    public function findOneById(int $id): ?Categories
    {
        return $this->categoriesRepository->findOneById($id);
    }
}