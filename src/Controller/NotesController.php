<?php
/**
 * Notes controller.
 */

namespace App\Controller;

use App\Entity\Notes;
use App\Form\NotesType;
use App\Repository\NotesRepository;
use Knp\Component\Pager\PaginatorInterface;
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
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Repository\NotesRepository           $notesRepository Notes repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="notes_index",
     * )
     */
    public function index(Request $request, NotesRepository $notesRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $notesRepository->queryAll(),
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
        return $this->render(
            'notes/show.html.twig',
            ['notes' => $notes]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Repository\NotesRepository            $notesRepository Notes repository
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
    public function create(Request $request, NotesRepository $notesRepository): Response
    {
        $notes = new Notes();
        $form = $this->createForm(NotesType::class, $notes);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notesRepository->save($notes);
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
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Entity\Notes                         $notes          Notes entity
     * @param \App\Repository\NotesRepository           $notesRepository Notes repository
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
    public function edit(Request $request, Notes $notes, NotesRepository $notesRepository): Response
    {
        $form = $this->createForm(NotesType::class, $notes, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notesRepository->save($notes);
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
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Entity\Notes                         $notes           Notes entity
     * @param \App\Repository\NotesRepository           $notesRepository Notes repository
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
    public function delete(Request $request, Notes $notes, NotesRepository $notesRepository): Response
    {
        $form = $this->createForm(FormType::class, $notes, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $notesRepository->delete($notes);
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