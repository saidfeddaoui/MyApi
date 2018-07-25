<?php

namespace App\Controller\BackOffice;

use App\Entity\Pack;
use App\Form\PackType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Services\FonctionDivers;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PackController extends Controller
{
    /**
     * @Route("/packs", name="packs",options={"expose"=true})
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(PackType::class,new Pack(),[
            'action' => $this->generateUrl('add_pack'),
        ]);

        $Packs = $em->getRepository('App:Pack')->findAll();

        return $this->render('pack/index.html.twig', [
            'page_title' => 'Liste des packs',
            'page_subtitle' => '',
            'packs'=>$Packs,
            "form"=>$form->createView()
        ]);

    }

    /**
     * @Route(path="/packs/add", name="add_pack", options={"expose"=true})
     * @param SessionInterface $session
     * @param  Request $request
     * @return Response
     */
    public function addPack(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $pack = new Pack();
        $form = $this->createForm(PackType::class,$pack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($pack);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Pack ajouté avec succès ');
        }else{
            $aErrors = FonctionDivers::getErrorsAsArray($form->getErrors(true,false));
            foreach ($aErrors as $iKey => $sError)
            {
                $this->get('session')->getFlashBag()->add('error', $sError);
            }
        }
        return  $this->redirect($this->generateUrl('packs'));
    }

    /**
     * @Route(path="/packs/edit/{id}", name="edit_pack", options={"expose"=true})
     *
     * @param Pack $pack
     * @param Request $request
     * @return Response
     */
    public function editPack(Pack $pack, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(PackType::class, $pack,[
            'action' => $this->generateUrl('edit_pack',array('id' => $pack->getId()))]);
        $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             $em->persist($pack);
             $em->flush();
             $this->get('session')->getFlashBag()->add('success', 'Pack modifié avec succès ');
             return  $this->redirect($this->generateUrl('packs'));
         }else{
             $aErrors = FonctionDivers::getErrorsAsArray($form->getErrors(true,false));
             foreach ($aErrors as $iKey => $sError)
             {
                 $this->get('session')->getFlashBag()->add('error', $sError);
             }
         }

        return  $this->render('pack/form.html.twig',array(
            'form'=>$form->createView() ));
    }

    /**
     * @Route(path="/packs/delete/{id}", name="delete_pack", options={"expose"=true})
     *
     * @param Pack $pack
     * @param Request $request
     * @return JsonResponse
     */
    public function deletePack(Pack $pack, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($pack);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Pack supprimé avec succès"
        ));
    }
}
