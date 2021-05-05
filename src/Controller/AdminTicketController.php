<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTicketController extends AbstractController
{
    const menu = "gestion";
    const sub_menu = "ticket";

    /**
     * @Route("/admin/ticket", name="admin_ticket")
     */
    public function index(TicketRepository $ticketRepository): Response
    {
        // S'il n'y a aucun ticket alors generer les tickets
        $tickets = $ticketRepository->findAll();
        //$tickets = $ticketRepository->liste(); dd($tickets);

        if (!$tickets) {
            $this->ticketGenerated();

            $this->addFlash('success', "Les tickets ont été générés avec succès!");
            $tickets = $ticketRepository->findAll();
        }

        return $this->render('admin_ticket/index.html.twig', [
            'tickets' => $tickets,
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * Generation des code de tickets
     *
     * @return bool
     */
    protected function ticketGenerated(): bool
    {
        $em = $this->getDoctrine()->getManager();

        for ($i = 1; $i <= 200; $i++)
        {
            $ticket = new Ticket();
            $code = $this->codification($i);
            $ticket->setCode($code);
            $ticket->setPrix(150000);
            $ticket->setMedia($code.'.png');

            $em->persist($ticket);
        }

        $em->flush();

        return true;
    }

    /**
     * Generation de codes
     * @param $i
     * @return string
     */
    protected function codification($i): String
    {
        if ($i < 10){
            $code = 'GLB2100'.$i;
        }elseif ($i < 100){
            $code = 'GLB210'.$i;
        }else{
            $code = 'GLB21'.$i;
        }

        return $code;
    }
}
