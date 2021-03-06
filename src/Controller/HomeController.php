<?php

namespace App\Controller;

use App\Entity\User;
use App\Utilities\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    protected $utils;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        //dd($this->utils->connexion());
        $verif = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username'=>'delrodie']);
        if (!$verif){
            $this->utils->initalisationUser();
            $this->addFlash('success', "Compte utilisateur initialisé avec succès!");
        }

        if (!$this->getUser()) return $this->redirectToRoute('app_login');

        return $this->render('home/index.html.twig', [
            'menu' => 'dashboard',
            'sub_menu' => 'Accueil'
        ]);
    }
}
