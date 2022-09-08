<?php

namespace App\Controller;

use App\Entity\BloodGroup;
use App\Entity\Requester;
use App\Entity\User;
use App\Form\RequesterType;
use App\Repository\RequesterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/requester")
 */
class RequesterController extends AbstractController
{
    /**
     * @Route("/", name="app_requester_index", methods={"GET"})
     */
    public function index(RequesterRepository $requesterRepository, UserInterface $user): Response
    {
        return $this->render('requester/index.html.twig', [
            'requesters' => $requesterRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="app_requester_new", methods={"GET", "POST"})
     */
    public function new(Request $request, RequesterRepository $requesterRepository): Response
    {
        $requester = new Requester();
        $form = $this->createForm(RequesterType::class, $requester);
        $form->handleRequest($request);
        $bloodGroups = $this->getDoctrine()->getRepository(BloodGroup::class)->findAll();
        foreach ($bloodGroups as $bloodGroup) {
            $result[] = $bloodGroup->getName();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if(!empty($image))
            {
                $filesystem = new Filesystem();
                if($requester->getImage())
                {
                    $filesystem->remove($this->getParameter('images_directory') . '/' . $requester->getImage());
                }

                $imageName = md5(uniqid()).'.'.$image->guessExtension();
                try
                {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $imageName
                    );
                }
                catch (FileException $e)
                {
                    $this->addFlash('danger', 'Could not upload the image.');
                    $this->redirectToRoute('app_requester_new');
                }

                $requester->setImage($imageName);
            }
            $requester->setUser($this->getUser());
            $requesterRepository->add($requester, true);

            return $this->redirectToRoute('app_requester_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('requester/new.html.twig', [
            'requester' => $requester,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_requester_show", methods={"GET"})
     */
    public function show(Requester $requester): Response
    {
        $bloodGroup = $this->getDoctrine()->getRepository(BloodGroup::class)->findOneBy([
            'id'=> $requester->getBloodGroup()
        ]);

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'id' =>$requester->getUser()
        ]);

        return $this->render('requester/show.html.twig', [
            'requester' => $requester,
            'bloodGroup' => $bloodGroup
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_requester_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Requester $requester, RequesterRepository $requesterRepository): Response
    {
        $form = $this->createForm(RequesterType::class, $requester);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $requesterRepository->add($requester, true);

            return $this->redirectToRoute('app_requester_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('requester/edit.html.twig', [
            'requester' => $requester,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_requester_delete", methods={"POST"})
     */
    public function delete(Request $request, Requester $requester, RequesterRepository $requesterRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$requester->getId(), $request->request->get('_token'))) {
            $requesterRepository->remove($requester, true);
        }

        return $this->redirectToRoute('app_requester_index', [], Response::HTTP_SEE_OTHER);
    }
}
