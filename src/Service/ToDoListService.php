<?php
/**
 * To Do List service.
 */

namespace App\Service;

use App\Entity\ToDoList;
use App\Repository\ToDoListRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ToDoListService.
 */
class ToDoListService
{
    /**
     * ToDoList repository.
     *
     * @var ToDoListRepository
     */
    private ToDoListRepository $todolistRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * Tags service.
     *
     * @var \App\Service\TagsService
     */
    private $tagsService;

    /**
     * ToDoListService constructor.
     *
     * @param ToDoListRepository        $todolistRepository     ToDoList repository
     * @param PaginatorInterface    $paginator          Paginator
     * @param TagsService            $tagsService         Tags service
     */
    public function __construct(ToDoListRepository $todolistRepository, PaginatorInterface $paginator, TagsService $tagsService)
    {
        $this->todolistRepository = $todolistRepository;
        $this->paginator = $paginator;
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
            $this->todolistRepository->queryByAuthor($user, $filters),
            $page,
            ToDoListRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save ToDoList.
     *
     * @param ToDoList $to_do_list ToDoList entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(ToDoList $to_do_list): void
    {
        $this->todolistRepository->save($to_do_list);
    }

    /**
     * Delete ToDoList.
     *
     * @param ToDoList $to_do_list ToDoList entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(ToDoList $to_do_list): void
    {
        $this->todolistRepository->delete($to_do_list);
    }

    /**
     * Prepare filters for the todolist list.
     *
     * @param array $filters Raw filters from request
     *
     * @return array Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (isset($filters['tags_id']) && is_numeric($filters['tags_id'])) {
            $tags = $this->tagsService->findOneById(
                $filters['tags_id']
            );
            if (null !== $tags) {
                $resultFilters['tags'] = $tags;
            }
        }

        return $resultFilters;
    }
}