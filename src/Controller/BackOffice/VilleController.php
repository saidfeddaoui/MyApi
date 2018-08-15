<?php

namespace App\Controller\BackOffice;

use App\Entity\Ville;
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
class VilleController extends Controller
{

    /**
     * @Route(path="/villes", name="list_ville", options={"expose"=true})
     *
     * @Breadcrumb(title="Villes")
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
        $villes = $em->getRepository('App:Ville')->findBy(['insuranceType'=> $session->get('insuranceType')]);
        foreach ($villes as $key => $value){
            $translations =  $repository->findTranslations($value);
            $data[] = array(
                'id' => $value->getId(),
                'nom' => $value->getNom(),
                'nom_ar' => $translations['ar']["nom"] ?? '',
            );
        }
        return $this->render('ville/index.html.twig', [
            'page_title' => 'Villes de réparation',
            'page_subtitle' => '',
            'data'=>$data
        ]);
    }
    /**
     * @Route(path="/villes/add", name="add_ville", options={"expose"=true})
     * @param SessionInterface $session
     * @param Request $request
     * @return JsonResponse
     */
    public function addVille(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $insuranceType = $em->getRepository('App:InsuranceType')->find($session->get('insuranceType')->getId());
        $iName = $request->request->get('name');
        $iName_ar = $request->request->get('name_ar');
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $ville = new Ville();
        $ville->setNom($iName);
        $ville->setInsuranceType($insuranceType);
        $em->persist($ville);
        $repository->translate($ville, 'nom', 'ar', $iName_ar) ;
        $em->flush();
        return  new JsonResponse([
            'id' => $ville->getId(),
            'message' => 'Ville ajoutée avec succès',
        ]);
    }
    /**
     * @Route(path="/villes/edit/{id}", name="edit_ville", options={"expose"=true})
     *
     * @param Ville $ville
     * @param Request $request
     * @return JsonResponse
     */
    public function editVille(Ville $ville, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iName = $request->request->get('name');
        $iName_ar = $request->request->get('name_ar');
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $ville->setNom($iName);
        $repository->translate($ville, 'nom', 'ar', $iName_ar) ;
        $em->persist($ville);
        $em->flush();
        return  new JsonResponse([
            'id' => $ville->getId(),
            'message' => 'Ville modifiée avec succès',
        ]);
    }
    /**
     * @Route(path="/villes/delete/{id}", name="delete_ville", options={"expose"=true})
     *
     * @param Ville $ville
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteVille(Ville $ville, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($ville);
        $em->flush();
        return  new JsonResponse(['message' => 'Ville supprimée avec succès']);
    }
}
