<?php
/**
 * To Do List controller.
 */

namespace App\Controller;

use App\Entity\ToDoList;
use App\Form\ToDoListType;
use App\Service\ToDoListService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
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
    /**
     * ToDoList service.
     *
     * @var ToDoListService
     */
    private ToDoListService $todolistService;

    /**
     * ToDoListController constructor.
     *
     * @param ToDoListService $todolistService ToDoList service
     */
    public function __construct(ToDoListService $todolistService)
    {
        $this->todolistService = $todolistService;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="todolist_index",
     * )
     */
    public function index(Request $request): Response
    {
        $filters = [];
        $filters['tags_id'] = $request->query->getInt('filters_tags_id');

        $pagination = $this->todolistService->createPaginatedList(
            $request->query->getInt('page', 1),
            $this->getUser(),
            $filters
        );

        return $this->render(
            'todolist/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\ToDoList $todolist ToDoList entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="todolist_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(ToDoList $todolist): Response
    {
        if ($todolist->getAuthor() !== $this->getUser()) {
            $this->addFlash('warning', 'message_item_not_found');

            return $this->redirectToRoute('todolist_index');
        }

        return $this->render(
            'todolist/show.html.twig',
            ['todolist' => $todolist]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="todolist_create",
     * )
     */
    public function create(Request $request): Response
    {
        $todolist = new ToDoList();
        $form = $this->createForm(ToDoListType::class, $todolist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todolist->setAuthor($this->getUser());
            $this->todolistService->save($todolist);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('todolist_index');
        }

        return $this->render(
            'todolist/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request  HTTP request
     * @param \App\Entity\ToDoList                      $todolist ToDoList entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="todolist_edit",
     * )
     */
    public function edit(Request $request, ToDoList $todolist): Response
    {
        if ($todolist->getAuthor() !== $this->getUser()) {
            $this->addFlash('warning', 'message_item_not_found');

            return $this->redirectToRoute('todolist_index');
        }

        $form = $this->createForm(ToDoListType::class, $todolist, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todolist->setAuthor($this->getUser());
            $this->todolistService->save($todolist);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('todolist_index');
        }

        return $this->render(
            'todolist/edit.html.twig',
            [
                'form' => $form->createView(),
                'todolist' => $todolist,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request  HTTP request
     * @param \App\Entity\ToDoList                      $todolist ToDoList entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="todolist_delete",
     * )
     */
    public function delete(Request $request, ToDoList $todolist): Response
    {
        if ($todolist->getAuthor() !== $this->getUser()) {
            $this->addFlash('warning', 'message.item_not_found');

            return $this->redirectToRoute('todolist_index');
        }

        $form = $this->createForm(FormType::class, $todolist, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->todolistService->delete($todolist);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('todolist_index');
        }

        return $this->render(
            'todolist/delete.html.twig',
            [
                'form' => $form->createView(),
                'todolist' => $todolist,
            ]
        );
    }
}
