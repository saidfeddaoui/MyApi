<?php

namespace App\Controller\BackOffice;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{

    /**
     * @Route("/users", name="list_user")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('App:User')->findAll();
        $form = $this->createForm(RegistrationType::class, new User(),[
            'action' => $this->generateUrl('add_user'),
        ]);

        return $this->render('@FOSUser/Registration/register.html.twig', [
            'page_title' => 'Gestion des utilisateurs',
            'page_subtitle' => '',
            'users' => $users,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route(path="/users/add", name="add_user", options={"expose"=true})
     *
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        return $this->redirectToRoute('list_user');
    }
    /**
     * @Route("/admin/users/edit/{id}", name="edit_user",options={"expose"=true})
     *
     * @param User $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(User $user, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(RegistrationType::class, $user, [
            'action' => $this->generateUrl('edit_user', ['id' => $user->getId()])
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            //$this->container->get('session')->getFlashBag()->add("success", "L'agent ".$oUser->getUsername()." a été mise à jour");
            return $this->redirectToRoute('list_user');
        }

        return $this->render('@FOSUser/Registration/edit.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Gestion des utilisateurs',
            'page_subtitle' => 'Modification'
        ]);
    }
    /**
     * @Route("/admin/remove/{id}", name="delete_user", options={"expose"=true})
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function remove(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'L\'Utilisateur a été supprimé avec succès');
        return $this->redirectToRoute('list_user');
    }

}
