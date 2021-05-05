<?php

namespace App\Controller;

use App\Entity\Participer;
use App\Entity\Ticket;
use App\Form\ParticiperEditType;
use App\Form\ParticiperType;
use App\Repository\ParticiperRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/participer")
 */
class ParticiperController extends AbstractController
{
    const menu ="gestion";
    const sub_menu = "affectation";

    /**
     * @Route("/", name="participer_index", methods={"GET"})
     */
    public function index(ParticiperRepository $participerRepository): Response
    {
        $listes = $participerRepository->findList(); //dd($listes);
        $participers = []; $i=0; $total=0; $couple=0; $invite=0; $gratuit=0;
        foreach ($listes as $liste){
            $participers[$i++] = [
                'nom' => $liste->getParticipant()->getNom(),
                'prenoms' => $liste->getParticipant()->getPrenoms(),
                'contact' => $liste->getParticipant()->getContact(),
                'ticket' => $liste->getTicket()->getCode(),
                'montant' => $liste->getMontant(),
                'place' => $liste->getPlace(),
                'type' => $liste->getType(),
                'id' => $liste->getId()
            ];
            // Calcul du montant total
            $total = $total+$liste->getMontant();
        } //dd($participers);
        return $this->render('participer/index.html.twig', [
            'listes' => $participers,
            'total' => $total,
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
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
        $form = $this->createForm(ParticiperEditType::class, $participer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket = $this->getDoctrine()->getRepository(Ticket::class)->findOneBy(['id'=>$request->get('_ticket')]);
            $participer->setTicket($ticket);
            $participer->setType($request->get('_type'));
            $ticket->setPlace($participer->getPlace());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Une table a bien été affectée au ticket ".$ticket->getCode());

            return $this->redirectToRoute('participer_index');
        }

        return $this->render('participer/edit.html.twig', [
            'participer' => $participer,
            'form' => $form->createView(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
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
