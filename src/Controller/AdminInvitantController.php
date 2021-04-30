<?php

namespace App\Controller;

use App\Entity\Invitant;
use App\Form\InvitantType;
use App\Repository\InvitantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/invitant")
 */
class AdminInvitantController extends AbstractController
{
    const menu = "invitant";
    const sub_menu = "invitant";

    /**
     * @Route("/", name="admin_invitant_index", methods={"GET"})
     */
    public function index(InvitantRepository $invitantRepository): Response
    {
        return $this->render('admin_invitant/index.html.twig', [
            'invitants' => $invitantRepository->findAllWithUser(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="admin_invitant_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $invitant = new Invitant();
        $form = $this->createForm(InvitantType::class, $invitant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Verification  de non affectation de l'utilisateur
            $verifUser = $this->getDoctrine()->getRepository(Invitant::class)->findOneBy(['user'=>$invitant->getUser()]);
            if ($verifUser){
                $this->addFlash('danger', "Echec! Le nom utilisateur ".$invitant->getUser()->getUsername()." a déjà été associé à un invitant");
                return $this->redirectToRoute('admin_invitant_new');
            }
            
            $entityManager->persist($invitant);
            $entityManager->flush();

            $this->addFlash('success', "L'invitant ".$invitant->getNom().' '.$invitant->getPrenoms()." a été crée avec succès");

            return $this->redirectToRoute('admin_invitant_index');
        }

        return $this->render('admin_invitant/new.html.twig', [
            'invitant' => $invitant,
            'form' => $form->createView(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_invitant_show", methods={"GET"})
     */
    public function show(Invitant $invitant): Response
    {
        return $this->render('admin_invitant/show.html.twig', [
            'invitant' => $invitant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_invitant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Invitant $invitant): Response
    {
        $form = $this->createForm(InvitantType::class, $invitant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'invitant ".$invitant->getNom().' '.$invitant->getPrenoms()." a été modifié avec succès");

            return $this->redirectToRoute('admin_invitant_index');
        }

        return $this->render('admin_invitant/edit.html.twig', [
            'invitant' => $invitant,
            'form' => $form->createView(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="admin_invitant_delete", methods={"POST"})
     */
    public function delete(Request $request, Invitant $invitant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invitant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($invitant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_invitant_index');
    }
}
