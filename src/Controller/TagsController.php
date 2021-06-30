<?php
/**
 * Tags controller.
 */

namespace App\Controller;

use App\Entity\Tags;
use App\Form\TagsType;
use App\Service\TagsService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TagController.
 *
 * @Route("/tags")
 */
class TagsController extends AbstractController
{
    /**
     * Tags service.
     *
     * @var TagsService
     */
    private TagsService $tagsService;

    /**
     * TagController constructor.
     *
     * @param TagsService $tagsService Tags service
     */
    public function __construct(TagsService $tagsService)
    {
        $this->tagsService = $tagsService;
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
     *     name="tags_index",
     * )
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->tagsService->createPaginatedList($page);

        return $this->render(
            'tags/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param Tags $tags Tags entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="tags_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Tags $tags): Response
    {
        return $this->render(
            'tags/show.html.twig',
            ['tags' => $tags]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="tags_create",
     * )
     */
    public function create(Request $request): Response
    {
        $tags = new Tags();
        $form = $this->createForm(TagsType::class, $tags);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagsService->save($tags);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('tags_index');
        }

        return $this->render(
            'tags/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Tags    $tags    Tags entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="tags_edit",
     * )
     */
    public function edit(Request $request, Tags $tags): Response
    {
        $form = $this->createForm(TagsType::class, $tags, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->save($tags);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('tags_index');
        }

        return $this->render(
            'tags/edit.html.twig',
            [
                'form' => $form->createView(),
                'tags' => $tags,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Tags    $tags    Tags entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="tags_delete",
     * )
     */
    public function delete(Request $request, Tags $tags): Response
    {
        if ($tags->getNotes()->count()) {
            $this->addFlash('warning', 'message_tags_contains_note');

            return $this->redirectToRoute('tags_index');
        }

        if ($tags->getTasks()->count()) {
            $this->addFlash('warning', 'message_tags_contains_tasks');

            return $this->redirectToRoute('tags_index');
        }

        $form = $this->createForm(FormType::class, $tags, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->delete($tags);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('tags_index');
        }

        return $this->render(
            'tags/delete.html.twig',
            [
                'form' => $form->createView(),
                'tags' => $tags,
            ]
        );
    }

    /**
     * Find by title.
     *
     * @param string $title tags title
     *
     * @return \App\Entity\Tags|null Tags entity
     */
    public function findOneByTitle(string $title): ?Tags
    {
        return $this->tagsRepository->findOneByTitle($title);
    }

    /**
     * Find tag by Id.
     *
     * @param int $id Tag Id
     *
     * @return \App\Entity\Tags|null Tag entity
     */
    public function findOneById(int $id): ?Tags
    {
        return $this->tagsRepository->findOneById($id);
    }
}
