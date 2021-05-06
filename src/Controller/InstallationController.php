<?php

namespace App\Controller;

use App\Entity\Participer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/installation")
 */
class InstallationController extends AbstractController
{
    const menu= 'dashboard';
    const sub_menu = 'installation';

    /**
     * @Route("/{ticket}-rcab2021", name="installation")
     */
    public function index($ticket): Response
    {
        $invite = $this->getDoctrine()->getRepository(Participer::class)->findOneByTicket($ticket);

        if (!$invite){
            $this->addFlash('danger', "Ce ticket n'est pas encore disponible");
            $invite = [];
        }

        return $this->render('installation/index.html.twig', [
            'menu' => self::menu,
            'sub_menu' => self::sub_menu,
            'invite' => $invite
        ]);
    }

    /**
     * @Route("/{ticket}/new/", name="installation_new", methods={"GET"})
     */
    public function new($ticket) : Response
    {
        $invite = $this->getDoctrine()->getRepository(Participer::class)->findOneByTicket($ticket);

        if (!$invite){
            $this->addFlash('danger', "Ce ticket n'a pas encore été affecté pour installer l'invité");
            $invite = [];
        }else{
            $invite->setInstall(true);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Installation effectuée avec succès!");
        }

        return $this->render('installation/index.html.twig', [
            'menu' => self::menu,
            'sub_menu' => self::sub_menu,
            'invite' => $invite
        ]);
    }
}
