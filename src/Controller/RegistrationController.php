<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegistrationController extends Controller
{

    /**
     * @Route("/admin/register", name="register")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('App:User')->findAll();
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()) {
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('register');
        }

        return $this->render('@FOSUser/Registration/register.html.twig', [
            'page_title' => 'Gestion des utilisateurs MAMDA',
            'page_subtitle' => '',
            'users' => $users,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/register/edit/{id}", name="edit_user",options={"expose"=true})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $oUser = $em->getRepository('App:User')->find($id);
        $form = $this->createForm(RegistrationType::class,$oUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($oUser);
            $em->flush();
            //$this->container->get('session')->getFlashBag()->add("success", "L'agent ".$oUser->getUsername()." a été mise à jour");
            return $this->redirectToRoute('register');
        }

        return $this->render('@FOSUser/Registration/edit.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Gestion des utilisateurs MAMDA',
            'page_subtitle' => ''
        ]);
    }

    /**
     * @Route("/admin/remove/{id}", name="remove_user", options={"expose"=true})
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $this->container->get('session')->getFlashBag()->add("success", "L'Utilisateur a été supprimé avec succés");
        return $this->redirectToRoute('register');
    }

}
