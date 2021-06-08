<?php
/**
 * To Do List controller.
 */

namespace App\Controller;

use App\Entity\Notes;
use App\Repository\NotesRepository;
use App\Repository\ToDoListRepository;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NotesController
 *
 * @Route("/notes")
 */
class NotesController extends AbstractController
{
    private ToDoListRepository $notesRepository;

    private PaginatorInterface $paginator;

    /**
     * NotesController contructor.
     *
     * @param ToDoListRepository $notesRepository
     * @param PaginatorInterface $paginator
     */
    public function __contruct(ToDoListRepository $todolistRepository, PaginatorInterface $paginator)
    {
        $this->notesRepository = $notesRepository;
        $this->paginator = $paginator;
    }


    /**
     * Index action.
     *
     * @param NotesRepository $notesRepository
     * @param Request $request
     * @param Paginator $paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="notes_index",
     * )
     */
    public function index(Request $request, NotesRepository $todolistRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $todolistRepository->queryAll(),
            $request->query->getInt('page', 1),
           NotesRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'notes/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param Notes $notes Notes
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="notes_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Notes $notes): Response
    {
        return $this->render(
            'notes/show.html.twig',
            ['notes' => $notes]
        );
    }
}