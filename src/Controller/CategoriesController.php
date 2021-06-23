<?php
/**
 * Categories controller.
 */

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Service\CategoriesService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoriesController.
 *
 * @Route("/categories")
 */
class CategoriesController extends AbstractController
{
    /**
     * Categories service.
     *
     * @var \App\Service\CategoriesService
     */
    private $categoriesService;

    /**
     * CategoriesController constructor.
     *
     * @param \App\Service\CategoriesService $categoriesService Categories service
     */
    public function __construct(CategoriesService $categoriesService)
    {
        $this->categoriesService = $categoriesService;
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
     *     name="categories_index",
     * )
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->categoriesService->createPaginatedList($page);

        return $this->render(
            'categories/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Categories $categories Categories entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="categories_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Categories $categories): Response
    {
        return $this->render(
            'categories/show.html.twig',
            ['categories' => $categories]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request     $request                HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="categories_create",
     * )
     */
    public function create(Request $request): Response
    {
        $categories = new Categories();
        $form = $this->createForm(CategoriesType::class, $categories);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesService->save($categories);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('categories_index');
        }

        return $this->render(
            'categories/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request     $request                HTTP request
     * @param \App\Entity\Categories                        $categories             Categories entity
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
     *     name="categories_edit",
     * )
     */
    public function edit(Request $request, Categories $categories): Response
    {
        $form = $this->createForm(CategoriesType::class, $categories, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesService->save($categories);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('categories_index');
        }

        return $this->render(
            'categories/edit.html.twig',
            [
                'form' => $form->createView(),
                'categories' => $categories,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Categories                      $categories   Category entity
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
     *     name="categories_delete",
     * )
     */
    public function delete(Request $request, Categories $categories): Response
    {
        if ($categories->getNotes()->count()) {
            $this->addFlash('warning', 'message_categories_contains_notes');

            return $this->redirectToRoute('categories_index');
        }

        $form = $this->createForm(FormType::class, $categories, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesService->delete($categories);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('categories_index');
        }

        return $this->render(
            'categories/delete.html.twig',
            [
                'form' => $form->createView(),
                'categories' => $categories,
            ]
        );
    }
}