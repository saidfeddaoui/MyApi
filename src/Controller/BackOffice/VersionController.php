<?php

namespace App\Controller\BackOffice;


use App\Entity\Versions;
use App\Form\VersionType;
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
     * @return Response
     */
    public function index()
    {
        $form = $this->createForm(VersionType::class, new Versions(), [
            'action' => $this->generateUrl('content_types_add_version'),
        ]);
        $em = $this->getDoctrine()->getManager();
        $version = $em->getRepository('App:Versions')->findAll();

        return $this->render('version/index.html.twig', [
            'page_title' => 'Version',
            'page_subtitle' => '',
            'data' => $version,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route(path="/version/add", name="add_version", options={"expose"=true})
     *
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
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
                $request->getSession()->getFlashBag()->add('error', 'une erreur est survenue lors de l\'ajout du controle de version !!');
                break;
            }
        }
        $request->getSession()->getFlashBag()->add('success', 'Controle de version bien ajouté !!');
        return  $this->redirect($this->generateUrl('content_types_versions'));
    }
    /**
     * @Route(path="/version/edit/{id}", name="edit_version", options={"expose"=true})
     *
     * @param Versions $version
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Versions $version, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(VersionType::class, $version,[
            'action' => $this->generateUrl('content_types_edit_version', ['id' => $version->getId()])
        ]);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($version);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Controle de version bien modifié!!');
            return $this->redirect($this->generateUrl('content_types_versions'));
        }
        return  $this->render('version/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route(path="/delete/{id}", name="delete_version", options={"expose"=true})
     *
     * @param Versions $version
     * @return JsonResponse
     */
    public function delete(Versions $version)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($version);
        $em->flush();
        return  new JsonResponse(['message' => 'Version supprimée avec succès']);
    }

}
