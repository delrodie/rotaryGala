<?php

namespace App\Controller;

use App\Entity\Participer;
use App\Form\ParticiperType;
use App\Repository\ParticiperRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/participer")
 */
class ParticiperController extends AbstractController
{
    /**
     * @Route("/", name="participer_index", methods={"GET"})
     */
    public function index(ParticiperRepository $participerRepository): Response
    {
        return $this->render('participer/index.html.twig', [
            'participers' => $participerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="participer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $participer = new Participer();
        $form = $this->createForm(ParticiperType::class, $participer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($participer);
            $entityManager->flush();

            return $this->redirectToRoute('participer_index');
        }

        return $this->render('participer/new.html.twig', [
            'participer' => $participer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="participer_show", methods={"GET"})
     */
    public function show(Participer $participer): Response
    {
        return $this->render('participer/show.html.twig', [
            'participer' => $participer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="participer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Participer $participer): Response
    {
        $form = $this->createForm(ParticiperType::class, $participer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('participer_index');
        }

        return $this->render('participer/edit.html.twig', [
            'participer' => $participer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="participer_delete", methods={"POST"})
     */
    public function delete(Request $request, Participer $participer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($participer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('participer_index');
    }
}
