<?php

namespace App\Controller\BackOffice;

use App\Entity\Attachment;
use App\Entity\Device;
use App\Form\DeviceType;
use Ramsey\Uuid\Uuid;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route(path="/content_types", name="content_types_")
 * @Security("is_granted('ROLE_CONTENT_MANAGEMENT')")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Device")
 */
class DeviceController extends Controller
{

    /**
     * @Route(name="list")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(DeviceType::class, new Device(), [
            'action' => $this->generateUrl('content_types_add_device'),
        ]);
        $em = $this->getDoctrine()->getManager();
        $device = $em->getRepository('App:Device')->findAll();

        return $this->render('device/index.html.twig', [
            'page_title' => 'device',
            'page_subtitle' => '',
            'data' => $device,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route(path="/add", name="content_types_add_device", options={"expose"=true})
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function add(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(DeviceType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Device $device
             */
            $device = $form->getData();

            $em->persist($device);
            $em->flush();
        }
        if ($errors = $form->getErrors()) {
            foreach ($errors as $error) {
                $this->addFlash('add.product.error', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('content_types_list'));
    }
    /**
     * @Route(path="/edit/{id}", name="content_types_edit_device", options={"expose"=true})
     *
     * @param Device $device
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Device $device, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(DeviceType::class, $device,[
            'action' => $this->generateUrl('content_types_edit_device', ['id' => $device->getId()])
        ]);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($device);
            $em->flush();
            return $this->redirect($this->generateUrl('content_types_list'));
        }
        return  $this->render('device/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route(path="/delete/{id}", name="content_types_delete_device", options={"expose"=true})
     *
     * @param Device $device
     * @return JsonResponse
     */
    public function delete(Device $device)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($device);
        $em->flush();
        return  new JsonResponse(['message' => 'Device supprimée avec succès']);
    }

}
