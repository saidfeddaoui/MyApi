<?php

namespace App\Controller\BackOffice;

use App\Entity\Ville;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class ExampleController extends Controller
{
    /**
     * @Route("/example", name="example", options={"expose"=true})
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('App/User')->findOneById(1);
        $user->
//        $ville = new Ville();
//        $ville->setNom('TANGER');
//        $ville->setLocale('ar')->setNom('Agadir (ru)');
//        $em->persist($ville);
//        $em->flush();
//        die();
//        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
//        $translations = $repository->findTranslations($ville);
//        dump($translations);
//        die;



//        $ville = new Ville();
//        $ville->setNom('Tanger');
//        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
//        $repository->translate($ville, 'nom', 'ar', 'Tanger(de)')
//        ;
//
//        $em->persist($ville);
       // $em->flush();
        $data = array();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $villes = $em->getRepository('App:Ville')->findAll();
        foreach ($villes as $key => $value){
            $translations =  $repository->findTranslations($value);
            $data[] = array('id'=>$value->getId(),'nom'=>$value->getNom(),'nom_ar'=>@$translations['ar']["nom"]);
        }
        var_dump($data);
        die();



        return $this->render('example/index.html.twig', [
            'page_title' => 'WELCOME FROM ' . $ville->getNom() . ' (' . $ville->getId() . ')',
        ]);
    }
}
