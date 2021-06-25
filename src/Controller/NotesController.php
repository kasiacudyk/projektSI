<?php
/**
 * Notes controller.
 */

namespace App\Controller;

use App\Entity\Notes;
use App\Form\NotesType;
use App\Service\NotesService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NotesController.
 *
 * @Route("/notes")
 */
class NotesController extends AbstractController
{
    /**
     * Notes service.
     *
     * @var NotesService
     */
    private NotesService $notesService;

    /**
     * NotesController constructor.
     *
     * @param NotesService $notesService Notes service
     */
    public function __construct(NotesService $notesService)
    {
        $this->notesService = $notesService;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="notes_index",
     * )
     */
    public function index(Request $request): Response
    {
        $filters = [];
        $filters['categories_id'] = $request->query->getInt('filters_categories_id');
        $filters['tags_id'] = $request->query->getInt('filters_tags_id');

        $pagination = $this->notesService->createPaginatedList(
            $request->query->getInt('page', 1),
            $this->getUser(),
            $filters
        );

        return $this->render(
            'notes/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Notes $notes Notes entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
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
        if ($notes->getAuthor() !== $this->getUser()) {
            $this->addFlash('warning', 'message_item_not_found');

            return $this->redirectToRoute('notes_index');
        }

        return $this->render(
            'notes/show.html.twig',
            ['notes' => $notes]
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
     *     name="notes_create",
     * )
     */
    public function create(Request $request): Response
    {
        $notes = new Notes();
        $form = $this->createForm(NotesType::class, $notes);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notes->setAuthor($this->getUser());
            $this->notesService->save($notes);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('notes_index');
        }

        return $this->render(
            'notes/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Notes                         $notes   Notes entity
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
     *     name="notes_edit",
     * )
     */
    public function edit(Request $request, Notes $notes): Response
    {
        if ($notes->getAuthor() !== $this->getUser()) {
            $this->addFlash('warning', 'message_item_not_found');

            return $this->redirectToRoute('notes_index');
        }

        $form = $this->createForm(NotesType::class, $notes, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notes->setAuthor($this->getUser());
            $this->notesService->save($notes);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('notes_index');
        }

        return $this->render(
            'notes/edit.html.twig',
            [
                'form' => $form->createView(),
                'notes' => $notes,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Notes                         $notes   Notes entity
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
     *     name="notes_delete",
     * )
     */
    public function delete(Request $request, Notes $notes): Response
    {
        if ($notes->getAuthor() !== $this->getUser()) {
            $this->addFlash('warning', 'message_item_not_found');

            return $this->redirectToRoute('notes_index');
        }

        $form = $this->createForm(FormType::class, $notes, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->notesService->delete($notes);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('notes_index');
        }

        return $this->render(
            'notes/delete.html.twig',
            [
                'form' => $form->createView(),
                'notes' => $notes,
            ]
        );
    }
}
