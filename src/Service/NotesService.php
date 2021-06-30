<?php
/**
 * Notes service.
 */

namespace App\Service;

use App\Entity\Notes;
use App\Repository\NotesRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class NotesService.
 */
class NotesService
{
    /**
     * Notes repository.
     *
     * @var NotesRepository
     */
    private $notesRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * Category service.
     *
     * @var CategoriesService
     */
    private $categoriesService;

    /**
     * Tag service.
     *
     * @var \App\Service\TagsService
     */
    private $tagsService;

    /**
     * NotesService constructor.
     *
     * @param NotesRepository          $notesRepository   Notes repository
     * @param PaginatorInterface       $paginator         Paginator
     * @param CategoriesService        $categoriesService Categories service
     * @param \App\Service\TagsService $tagsService       Tags service
     */
    public function __construct(NotesRepository $notesRepository, PaginatorInterface $paginator, CategoriesService $categoriesService, TagsService $tagsService)
    {
        $this->notesRepository = $notesRepository;
        $this->paginator = $paginator;
        $this->categoriesService = $categoriesService;
        $this->tagsService = $tagsService;
    }

    /**
     * Create paginated list.
     *
     * @param int           $page    Page number
     * @param UserInterface $user    User entity
     * @param array         $filters Filters array
     *
     * @return PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page, UserInterface $user, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->notesRepository->queryByAuthor($user, $filters),
            $page,
            NotesRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save note.
     *
     * @param Notes $notes Notes entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Notes $notes): void
    {
        $this->notesRepository->save($notes);
    }

    /**
     * Delete note.
     *
     * @param Notes $notes Notes entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Notes $notes): void
    {
        $this->notesRepository->delete($notes);
    }

    /**
     * Prepare filters for the notes list.
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

        if (isset($filters['tags_id']) && is_numeric($filters['tags_id'])) {
            $tags = $this->tagsService->findOneById($filters['tags_id']);
            if (null !== $tags) {
                $resultFilters['tags'] = $tags;
            }
        }

        return $resultFilters;
    }
}
