<?php

namespace App\Controller;

use App\Entity\BloodGroup;
use App\Form\BloodGroupType;
use App\Repository\BloodGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blood/group")
 */
class BloodGroupController extends AbstractController
{
    /**
     * @Route("/", name="app_blood_group_index", methods={"GET"})
     */
    public function index(BloodGroupRepository $bloodGroupRepository): Response
    {
        return $this->render('blood_group/index.html.twig', [
            'blood_groups' => $bloodGroupRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_blood_group_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BloodGroupRepository $bloodGroupRepository): Response
    {
        $bloodGroup = new BloodGroup();
        $form = $this->createForm(BloodGroupType::class, $bloodGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bloodGroupRepository->add($bloodGroup, true);

            return $this->redirectToRoute('app_blood_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blood_group/new.html.twig', [
            'blood_group' => $bloodGroup,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_blood_group_show", methods={"GET"})
     */
    public function show(BloodGroup $bloodGroup): Response
    {
        return $this->render('blood_group/show.html.twig', [
            'blood_group' => $bloodGroup,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_blood_group_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, BloodGroup $bloodGroup, BloodGroupRepository $bloodGroupRepository): Response
    {
        $form = $this->createForm(BloodGroupType::class, $bloodGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bloodGroupRepository->add($bloodGroup, true);

            return $this->redirectToRoute('app_blood_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blood_group/edit.html.twig', [
            'blood_group' => $bloodGroup,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_blood_group_delete", methods={"POST"})
     */
    public function delete(Request $request, BloodGroup $bloodGroup, BloodGroupRepository $bloodGroupRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bloodGroup->getId(), $request->request->get('_token'))) {
            $bloodGroupRepository->remove($bloodGroup, true);
        }

        return $this->redirectToRoute('app_blood_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
