<?php

namespace App\Controller\BackOffice;
use App\Entity\ListSatisfaction;
use App\Entity\CirconstanceSinistre;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route(path="/devis", name="devis_")
 * @Security("is_granted('ROLE_DEVIS')")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Devis")
 */
class ListSatisfactionController extends Controller
{
    /**
     * @Route(path="/list-satisfaction", name="list_satisfaction", options={"expose"=true})
     *
     * @Breadcrumb(title="Liste Satisfaction")
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
        $listSatisfaction = $em->getRepository('App:ListSatisfaction')->findAll();
        foreach ($listSatisfaction as $key => $value){
            $translations =  $repository->findTranslations($value);
            $data[] = array(
                'id' => $value->getId(),
                'title' => $value->getTitle(),
                'title_ar' => $translations['ar']["title"] ?? '',
            );
        }
        return $this->render('list_satisfaction/index.html.twig', [
            'page_title' => 'Liste Satisfaction',
            'page_subtitle' => '',
            'data'=>$data
        ]);
    }


    /**
     * @Route(path="/list-satisfaction/add", name="add_list-satisfaction", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function addListSatisfaction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $title = $request->request->get('title');
        $title_ar = $request->request->get('title_ar');
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $listSatisfaction = new ListSatisfaction();
        $listSatisfaction->setTitle($title);
        $em->persist($listSatisfaction);
        $repository->translate($listSatisfaction, 'title', 'ar', $title_ar) ;
        $em->flush();
        return  new JsonResponse([
            'id' => $listSatisfaction->getId(),
            'message' => 'Liste satisfaction ajoutée avec succès',
        ]);
    }


    /**
     * @Route(path="/list-satisfaction/edit/{id}", name="edit_list-satisfaction", options={"expose"=true})
     *
     * @param ListSatisfaction $listSatisfaction
     * @param Request $request
     * @return JsonResponse
     */
    public function editListSatisfaction(ListSatisfaction $listSatisfaction, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $title = $request->request->get('title');
        $title_ar = $request->request->get('title_ar');
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $listSatisfaction->setTitle($title);
        $repository->translate($listSatisfaction, 'title', 'ar', $title_ar) ;
        $em->persist($listSatisfaction);
        $em->flush();
        return  new JsonResponse([
            'id' => $listSatisfaction->getId(),
            'message' => 'Liste satisfaction modifiée avec succès',
        ]);
    }

    /**
     * @Route(path="/list-satisfaction/delete/{id}", name="delete_list-satisfaction", options={"expose"=true})
     *
     * @param ListSatisfaction $listSatisfaction
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteListSatisfaction(ListSatisfaction $listSatisfaction, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($listSatisfaction);
        $em->flush();
        return  new JsonResponse(['message' => 'Liste satisfaction supprimée avec succès']);
    }

}
