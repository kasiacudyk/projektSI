<?php
/**
 * To Do List controller.
 */

namespace App\Controller;

use App\Entity\ToDoList;
use App\Repository\ToDoListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ToDoListController
 *
 * @Route("/todolist")
 */

class ToDoListController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \App\Repository\ToDoListRepository $todolistRepository To Do List repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="task_index",
     * )
     */
    public function index(ToDoListRepository $toDoListRepository): Response
    {
        return $this->render(
            'todolist/index.html.twig',
            ['todolist' => $toDoListRepository->findAll()]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\ToDoList $toDoList To Do List entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="task_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(ToDoList $toDoList): Response
    {
        return $toDoList->render(
            'todolist/show.html.twig',
            ['toDoList' => $toDoList]
        );
    }
}