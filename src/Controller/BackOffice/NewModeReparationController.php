<?php
/**
 * Created by PhpStorm.
 * User: mobiblanc
 * Date: 08/10/2018
 * Time: 11:24
 */

namespace App\Controller\BackOffice;

use App\Entity\ModeReparation;
use App\Form\NewModeReparationType;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


/**
 * @Route(path="/modes_reparation", name="modes_reparation_")
 * @Security("is_granted('ROLE_CONTENT_MANAGEMENT')")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Mode Réparation")
 */
class NewModeReparationController extends Controller
{

    /**
     * @Route(path="/",name="index")
     * @return Response
     */
    public function index()
    {
        $form = $this->createForm(NewModeReparationType::class, new ModeReparation(), [
            'action' => $this->generateUrl('modes_reparation_add'),
        ]);
        $em = $this->getDoctrine()->getManager();
        $mode = $em->getRepository('App:ModeReparation')->findAll();

        return $this->render('modeReparation/index.html.twig', [
            'page_title' => 'Mode Réparation',
            'page_subtitle' => '',
            'data' => $mode,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route(path="/add", name="add", options={"expose"=true})
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function add(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(NewModeReparationType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Device $device
             */
            $mode = $form->getData();

            $em->persist($mode);
            $em->flush();
        }
        if ($errors = $form->getErrors()) {
            foreach ($errors as $error) {
                $this->addFlash('add.product.error', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('modes_reparation_index'));
    }

    /**
     * @Route(path="/edit/{id}", name="edit", options={"expose"=true})
     *
     * @param ModeReparation $mode
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(ModeReparation $mode, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(DeviceType::class, $mode,[
            'action' => $this->generateUrl('modes_reparation_edit', ['id' => $mode->getId()])
        ]);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //$em->persist($mode);
            $em->flush();
            return $this->redirect($this->generateUrl('modes_reparation_index'));
        }
        return  $this->render('modeReparation/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/delete/{id}", name="delete", options={"expose"=true})
     *
     * @param ModeReparation $mode
     * @return JsonResponse
     */
    public function delete(ModeReparation $mode)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($mode);
        $em->flush();
        return  new JsonResponse(['message' => 'Mode Réparation supprimée avec succès']);
    }




}