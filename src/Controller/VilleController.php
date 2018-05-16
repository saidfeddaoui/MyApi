<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class VilleController extends Controller
{

    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ( $request->isXmlHttpRequest() ){

            if($request->request->get('type') == "DELETE"){
                $iName = $request->request->get('name');
                $iCity = $em->getRepository('App:Ville')->findOneByNom($iName);
                $em->remove($iCity);
                $em->flush();
                return  new JsonResponse(array(
                    "message" => "Ville supprimée avec succès"
                ));
            }else{

                $iName = $request->request->get('name');
                $iName_ar = $request->request->get('name_ar');
                $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
                $iCity = $em->getRepository('App:Ville')->findOneByNom($iName);

                if($iCity){
                    $iCity->setNom($iName);
                    $repository->translate($iCity, 'nom', 'ar',$iName_ar) ;
                    $em->persist($iCity);
                    $em->flush();
                    return  new JsonResponse(array(
                        "message" => "Ville modifiée avec succès"
                    ));

                }else{
                    $iCity = new Ville();
                    $iCity->setNom($iName);
                    $em->persist($iCity);
                    $repository->translate($iCity, 'nom', 'ar',$iName_ar) ;
                    $em->flush();
                    return  new JsonResponse(array(
                        "message" => "Ville ajoutée avec succès"
                    ));
                }
            }
        }

        $data = array();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $villes = $em->getRepository('App:Ville')->findAll();
        foreach ($villes as $key => $value){
            $translations =  $repository->findTranslations($value);
            $data[] = array('id'=>$value->getId(),'nom'=>$value->getNom(),'nom_ar'=>@$translations['ar']["nom"]);
        }
        return $this->render('ville/index.html.twig', [
            'page_title' => 'Villes de réparation',
            'page_subtitle' => '',
            'data'=>$data
        ]);


    }
}
