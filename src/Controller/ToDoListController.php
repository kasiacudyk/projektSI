<?php
/**
 * To Do List controller.
 */

namespace App\Controller;

use App\Entity\ToDoList;
use App\Repository\ToDoListRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ToDoListController
 *
 * @Route("/todolist")
 */
class ToDoListController extends AbstractController
{
    private ToDoListRepository $todolistRepository;

    private PaginatorInterface $paginator;

    /**
     * ToDoListController contructor.
     *
     * @param ToDoListRepository $todolistRepository
     * @param PaginatorInterface $paginator
     */
    public function __contruct(ToDoListRepository $todolistRepository, PaginatorInterface $paginator)
    {
        $this->todolistRepository = $todolistRepository;
        $this->paginator = $paginator;
    }


    /**
     * Index action.
     *
     * @param ToDoListRepository $todolistRepository
     * @param Request $request
     * @param Paginator $paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="todolist_index",
     * )
     */
    public function index(Request $request, ToDoListRepository $todolistRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $todolistRepository->queryAll(),
            $request->query->getInt('page', 1),
            ToDoListRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'todolist/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param ToDoList $to_do_list To Do List entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="todolist_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(ToDoList $to_do_list): Response
    {
        return $this->render(
            'todolist/show.html.twig',
            ['to_do_list' => $to_do_list]
        );
    }
}