<?php
/** ...... */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  Class HelloWorldController.
 *
 * @Route(
 *      "/hello-world"
 * )
 */
class HelloWorldController extends AbstractController
{
    /**
     * Index action.
     *
     * @param string $name User input
     *
     * @return Response HTTP response
     *
     * @Route(
     *      "/{name}",
     *     methods={"GET"},
     *     name="hello-world_index",
     *     requirements={"name": "[a-zA-Z]+"},
     *     defaults={"name": "World"},
     * )
     */
    public function index(string $name): Response
    {
        return $this->render(
            'hello-world/index.html.twig',
            ['name' => $name]
        );
    }
}