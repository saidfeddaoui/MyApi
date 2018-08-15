<?php

namespace App\Controller\BackOffice;

use App\Entity\CirconstanceSinistre;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
 * @Breadcrumb(title="Gestion Contenu")
 */
class CirconstanceSinistreController extends Controller
{
    /**
     * @Route(path="/circonstance-sinistre", name="list_CirconstanceSinistre", options={"expose"=true})
     *
     * @Breadcrumb(title="Les circonstances Sinistre")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $data = array();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $circonstanceSinistres = $em->getRepository('App:CirconstanceSinistre')->findBy(['insuranceType'=> $session->get('insuranceType')]);
        foreach ($circonstanceSinistres as $key => $value){
            $translations =  $repository->findTranslations($value);
            $data[] = array(
                'id' => $value->getId(),
                'title' => $value->getTitle(),
                'title_ar' => $translations['ar']["title"] ?? '',
            );
        }
        return $this->render('circonstanceSinistre/index.html.twig', [
            'page_title' => 'Les circonstances sinistre',
            'page_subtitle' => '',
            'data'=>$data
        ]);
    }


    /**
     * @Route(path="/circonstance-sinistre/add", name="add_circonstance-sinistre", options={"expose"=true})
     * @param SessionInterface $session
     * @param Request $request
     * @return JsonResponse
     */
    public function addCirconstanceSinistre(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $insuranceType = $em->getRepository('App:InsuranceType')->find($session->get('insuranceType')->getId());
        $title = $request->request->get('title');
        $title_ar = $request->request->get('title_ar');
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $circonstanceSinistre = new CirconstanceSinistre();
        $circonstanceSinistre->setTitle($title);
        $circonstanceSinistre->setInsuranceType($insuranceType);
        $em->persist($circonstanceSinistre);
        $repository->translate($circonstanceSinistre, 'title', 'ar', $title_ar) ;
        $em->flush();
        return  new JsonResponse([
            'id' => $circonstanceSinistre->getId(),
            'message' => 'Circonstance sinistre ajoutée avec succès',
        ]);
    }


    /**
     * @Route(path="/circonstance-sinistre/edit/{id}", name="edit_circonstance-sinistre", options={"expose"=true})
     *
     * @param CirconstanceSinistre $circonstanceSinistre
     * @param Request $request
     * @return JsonResponse
     */
    public function editCirconstanceSinistre(CirconstanceSinistre $circonstanceSinistre, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $title = $request->request->get('title');
        $title_ar = $request->request->get('title_ar');
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $circonstanceSinistre->setTitle($title);
        $repository->translate($circonstanceSinistre, 'title', 'ar', $title_ar) ;
        $em->persist($circonstanceSinistre);
        $em->flush();
        return  new JsonResponse([
            'id' => $circonstanceSinistre->getId(),
            'message' => 'Circonstance sinistre modifiée avec succès',
        ]);
    }

    /**
     * @Route(path="/circonstance-sinistre/delete/{id}", name="delete_circonstance-sinistre", options={"expose"=true})
     *
     * @param CirconstanceSinistre $circonstanceSinistre
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteCirconstanceSinistre(CirconstanceSinistre $circonstanceSinistre, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($circonstanceSinistre);
        $em->flush();
        return  new JsonResponse(['message' => 'Circonstance sinistre supprimée avec succès']);
    }

}
