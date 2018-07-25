<?php

namespace App\Controller\BackOffice;

use App\Entity\Accident;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route(path="/content_types", name="content_types_")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Gestion Contenu")
 */
class AccidentController extends Controller
{

    /**
     * @Route(path="/accident", name="type_accident", options={"expose"=true})
     *
     * @Breadcrumb(title="Types accident")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $accident = $em->getRepository('App:Accident')->findBy(['insuranceType'=> $session->get('insuranceType')]);
        $data = [];
        foreach ($accident as $key => $value){
            $translations =  $repository->findTranslations($value);
            $data[] = [
                'id' => $value->getId(),
                'type' => $value->getType(),
                'type_ar' => $translations['ar']['type'] ?? '',
            ];
        }
        return $this->render('accident/index.html.twig', [
            'page_title' => 'Types accident',
            'page_subtitle' => '',
            'data' => $data
        ]);
    }
    /**
     * @Route(path="/accident/add", name="add_accident", options={"expose"=true})
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return JsonResponse
     */
    public function addAccident(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $insuranceType = $em->getRepository('App:InsuranceType')->find($session->get('insuranceType')->getId());
        $iType = $request->request->get('type');
        $iType_ar = $request->request->get('type_ar');
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $accident = new Accident();
        $accident->setType($iType);
        $accident->setInsuranceType($insuranceType);
        $em->persist($accident);
        $repository->translate($accident, 'type', 'ar', $iType_ar) ;
        $em->flush();
        return  new JsonResponse([
            'id' => $accident->getId(),
            'message' => 'Type accident ajouté avec succès',
        ]);
    }
    /**
     * @Route(path="/accident/edit/{id}", name="edit_accident", options={"expose"=true})
     *
     * @param Accident $accident
     * @param Request $request
     * @return JsonResponse
     */
    public function editAccident(Accident $accident, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iType = $request->request->get('type');
        $iType_ar = $request->request->get('type_ar');
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $accident->setType($iType);
        $repository->translate($accident, 'type', 'ar', $iType_ar) ;
        $em->persist($accident);
        $em->flush();
        return  new JsonResponse([
            'id' => $accident->getId(),
            'message' => 'type accident modifié avec succès',
        ]);
    }
    /**
     * @Route(path="/accident/delete/{id}", name="delete_accident", options={"expose"=true})
     *
     * @param Accident $accident
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAccident(Accident $accident, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($accident);
        $em->flush();
        return  new JsonResponse(['message' => 'type accident supprimé avec succès']);
    }

}
