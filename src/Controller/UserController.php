<?php
/**
 * Users controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UsersdataType;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UsersController.
 *
 * @Route("/users")
 *
 */
class UserController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request            $request        HTTP request
     * @param UserRepository     $userRepository User repository
     * @param PaginatorInterface $paginator      Paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="users_index",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $userRepository->queryAll(),
            $request->query->getInt('page', 1),
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'users/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param User $user User entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="users_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(User $user): Response
    {
        return $this->render(
            'users/show.html.twig',
            ['users' => $user]
        );
    }

    /**
     * Edit action.
     *
     * @param Request        $request        HTTP request
     * @param User           $user
     * @param UserRepository $userRepository
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
     *     name="users_edit",
     * )
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        if ([User::ROLE_ADMIN]) {
            $form = $this->createForm(UsersdataType::class, $user, ['method' => 'PUT']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $newPassword = $form->get('newPassword')->getData();
                $userRepository->save($user, $newPassword);

                $this->addFlash('success', 'message_updated_successfully');

                return $this->redirectToRoute('notes_index');
            }

            return $this->render(
                'users/edit.html.twig',
                [
                    'form' => $form->createView(),
                    'users' => $user,
                ]
            );
        } else {
            $form = $this->createForm(UsersdataType::class, $user, ['method' => 'PUT']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $newPassword = $form->get('newPassword')->getData();
                $userRepository->save($user, $newPassword);

                $this->addFlash('success', 'message_updated_successfully');

                return $this->redirectToRoute('notes_index');
            }

            return $this->render(
                'users/edit.html.twig',
                [
                    'form' => $form->createView(),
                    'users' => $user,
                ]
            );
        }
    }
}
