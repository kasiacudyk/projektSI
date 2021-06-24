<?php
/**
 * Tags service.
 */

namespace App\Service;

use App\Entity\Tags;
use App\Repository\TagsRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TagsService.
 */
class TagsService
{
    /**
     * Tag repository.
     *
     * @var TagsRepository
     */
    private TagsRepository $tagsRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * TagService constructor.
     *
     * @param TagsRepository     $tagsRepository Tags repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(TagsRepository $tagsRepository, PaginatorInterface $paginator)
    {
        $this->tagsRepository = $tagsRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->tagsRepository->queryAll(),
            $page,
            TagsRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save tags.
     *
     * @param Tags $tags Tags entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Tags $tags): void
    {
        $this->tagsRepository->save($tags);
    }

    /**
     * Delete tag.
     *
     * @param Tags $tags Tag entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Tags $tags): void
    {
        $this->tagsRepository->delete($tags);
    }

    /**
     * Find tag by Id.
     *
     * @param int $id Tags Id
     *
     * @return Tags|null Tags entity
     */
    public function findOneById(int $id): ?Tags
    {
        return $this->tagsRepository->findOneById($id);
    }

    /**
     * Find by name
     *
     * @param string $name tag name
     *
     * @return \App\Entity\Tags|null Tag entity
     */
    public function findOneByName(string $name): ?Tags
    {
        return $this->tagsRepository->findOneByName($name);
    }
}
