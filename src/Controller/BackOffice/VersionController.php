<?php

namespace App\Controller\BackOffice;

use App\Entity\Attachment;
use App\Entity\Version;
use App\Form\VersionType;
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
 * @Breadcrumb(title="Version")
 */
class VersionController extends Controller
{

    /**
     * @Route(path="/versions",name="versions")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(VersionType::class, new Version(), [
            'action' => $this->generateUrl('content_types_add_version'),
        ]);
        $em = $this->getDoctrine()->getManager();
        $version = $em->getRepository('App:Version')->findAll();

        return $this->render('version/index.html.twig', [
            'page_title' => 'Version',
            'page_subtitle' => '',
            'data' => $version,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route(path="/add", name="add_version", options={"expose"=true})
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function add(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(VersionType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Version $version
             */
            $version = $form->getData();

            $em->persist($version);
            $em->flush();
        }
        if ($errors = $form->getErrors()) {
            foreach ($errors as $error) {
                $this->addFlash('add.product.error', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('content_types_versions'));
    }
    /**
     * @Route(path="/edit/{id}", name="edit_version", options={"expose"=true})
     *
     * @param Version $version
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Version $version, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(VersionType::class, $version,[
            'action' => $this->generateUrl('content_types_edit_versions', ['id' => $version->getId()])
        ]);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($version);
            $em->flush();
            return $this->redirect($this->generateUrl('content_types_versions'));
        }
        return  $this->render('version/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route(path="/delete/{id}", name="delete_version", options={"expose"=true})
     *
     * @param Version $version
     * @return JsonResponse
     */
    public function delete(Version $version)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($version);
        $em->flush();
        return  new JsonResponse(['message' => 'Version supprimée avec succès']);
    }

}
