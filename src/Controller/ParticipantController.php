<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Participer;
use App\Entity\Ticket;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use App\Utilities\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/participant")
 */
class ParticipantController extends AbstractController
{
    const menu = 'vente';
    const sub_menu = 'vente';

    private $utils;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    /**
     * @Route("/", name="participant_index", methods={"GET"})
     */
    public function index(ParticipantRepository $participantRepository): Response
    {
        //dd($participantRepository->findAll());
        return $this->render('participant/index.html.twig', [
            'participants' => $participantRepository->findAll(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="participant_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $participant = new Participant();
/*
        $ticket = $this->getDoctrine()->getRepository(Ticket::class)->findOneBy(['id'=>4]);

        $participer1 = new Participer();
        $participer1->setType('IN');
        $participer1->setTicket($ticket);
        $participant->getTickets()->add($participer1);
        $participer2 = new Participer();
        $participer2->setType('IN');
        $participer2->setTicket($ticket);
        $participant->getTickets()->add($participer2);
*/
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $ventes = $participant->getTickets();
            foreach ($ventes as $vente){
                $vente->setParticipant($participant);
                $vente->setMontant($this->utils->montantTicket($vente->getType())); //dd($vente);
                $entityManager->persist($vente);
            }
            $entityManager->persist($participant);
            $entityManager->flush();

            foreach ($ventes as $vente){
                $ticket = $vente->getTicket();
                $ticket->setRemise($vente->getMontant());
                $ticket->setStatut(true);
                $entityManager->persist($ticket);
            }

            $entityManager->flush();

            return $this->redirectToRoute('participant_index');
        }

        return $this->render('participant/new.html.twig', [
            'participant' => $participant,
            'form' => $form->createView(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="participant_show", methods={"GET"})
     */
    public function show(Participant $participant): Response
    {
        return $this->render('participant/show.html.twig', [
            'participant' => $participant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="participant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Participant $participant): Response
    {
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('participant_index');
        }

        return $this->render('participant/edit.html.twig', [
            'participant' => $participant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="participant_delete", methods={"POST"})
     */
    public function delete(Request $request, Participant $participant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($participant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('participant_index');
    }
}
