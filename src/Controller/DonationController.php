<?php

namespace App\Controller;

use App\Entity\Donation;
use App\Entity\Requester;
use App\Form\DonationType;
use App\Repository\DonationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/donation")
 */
class DonationController extends AbstractController
{
    /**
     * @Route("/", name="app_donation_index", methods={"GET"})
     */
    public function index(DonationRepository $donationRepository): Response
    {
        $user = $this->getUser();;

        if(in_array(UserController::ROLE_ADMIN, $this->getUser()->getRoles(), true)) {
            $donations = $donationRepository->findAll();
        } elseif(in_array(UserController::ROLE_USER, $this->getUser()->getRoles(), true)) {
            $donations = $donationRepository->findBy(['user' => $user->getId()]);
        } else {
            $donations = [];
        }

        return $this->render('donation/index.html.twig', [
            'donations' => $donations,
        ]);
    }

    /**
     * @Route("/new/{id}", name="app_donation_new", methods={"GET", "POST"})
     * @param Request $request
     * @param DonationRepository $donationRepository
     * @param Requester $requester
     * @return Response
     */
    public function new(Request $request, DonationRepository $donationRepository, Requester $requester): Response
    {
        $donation = new Donation();
        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donation->setUser($this->getUser());
            $donation->setRequester($requester);
            $donationRepository->add($donation, true);
            $this->addFlash('success', 'Donatia ta a fost inregistrata');
            return $this->redirectToRoute('app_donation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('donation/new.html.twig', [
            'donation' => $donation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_donation_show", methods={"GET"})
     */
    public function show(Donation $donation): Response
    {
        return $this->render('donation/show.html.twig', [
            'donation' => $donation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_donation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Donation $donation, DonationRepository $donationRepository): Response
    {
        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donationRepository->add($donation, true);

            return $this->redirectToRoute('app_donation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('donation/edit.html.twig', [
            'donation' => $donation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_donation_delete", methods={"POST"})
     */
    public function delete(Request $request, Donation $donation, DonationRepository $donationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donation->getId(), $request->request->get('_token'))) {
            $donationRepository->remove($donation, true);
            $this->addFlash('success', 'Donatia a fost stearsa cu succes!');
        }
        return $this->redirectToRoute('app_donation_index', [], Response::HTTP_SEE_OTHER);
    }
}
