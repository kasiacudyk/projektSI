<?php
/**
 * Record controller.
 */

namespace App\Controller;

use App\Repository\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RecordController.
 *
 * @Route("/record")
 */
class RecordController extends AbstractController
{
    /**
     * Record repository.
     *
     * @var \App\Repository\RecordRepository
     */
    private RecordRepository $repository;

    /**
     * RecordController constructor.
     *
     * @param \App\Repository\RecordRepository $repository Record repository
     */

    public function __construct(RecordRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Index action.
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="record_index",
     * )
     */
    public function index(): Response
    {
        $data = $this->repository->findAll();
        return $this->render(
            'record/index.html.twig',
            ['data' => $data]
        );
    }

    /**
     * Show action.
     *
     * @param int $id Record id
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="record_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(int $id): Response
    {
        $item = $this->repository->findById($id);
        return $this->render(
            'record/show.html.twig',
            ['item' => $item]
        );
    }

}