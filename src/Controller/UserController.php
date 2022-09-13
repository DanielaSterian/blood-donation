<?php

namespace App\Controller;

use App\Entity\BloodGroup;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_USER = 'ROLE_USER';

    /**
     * @Route("/", name="app_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $roles = [self::ROLE_ADMIN, self::ROLE_USER];
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'roles' => $roles,
        ]);
    }

    /**
     * @Route("/new", name="app_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'forUser' => true,
            'forAdmin' => false,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/profile", name="app_user_show", methods={"GET"})
     */
    public function show(): Response
    {
        $user = $this->getUser();
        $userData = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $user->getUsername()]);

        $bloodGroup = $this->getDoctrine()->getRepository(BloodGroup::class)->findOneBy([
            'id'=> $userData->getBloodGroup()
        ]);
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'bloodGroup' => $bloodGroup,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'forUser' => true,
            'forAdmin' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            try{
                $userRepository->remove($user, true);
                $this->addFlash('success', 'Utilizatorul a fost stears cu succes!');
            }catch(ForeignKeyConstraintViolationException $e) {
                $this->addFlash('fail', 'Nu poti sterge acest utilizator');
            }

        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/changeRole", name="change_user_role", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function changeRole(Request $request): Response
    {

        $role = $request->query->get('userRole');
        var_dump('request: ', $role);
        $idUser = $request->query->get('idUser');

        if($this->changeUserRole($idUser, $role))
        {
            $this->addFlash('success', 'Role successfully changed');
        }
        else
        {
            $this->addFlash('fail', 'User does not exist');
        }

        return $this->redirect($this->generateUrl('app_user_index'));
    }

    /**
     * change the role of an existing user
     * @param int $idUser
     * @param int $idRole
     * @return bool
     */
    public function changeUserRole(int $idUser, $role): bool
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id'=>$idUser]);

        if($user != null)
        {
            $role = $role == self::ROLE_ADMIN ? self::ROLE_ADMIN : self::ROLE_USER;
            $user->setRoles([$role]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return true;
        }
        return false;
    }
}
