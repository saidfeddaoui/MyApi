<?php

namespace App\Controller;

use App\Entity\Ville;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ExampleController extends Controller
{
    /**
     * @Route("/example", name="example")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ville = $em->getRepository('App:Ville')->findOneById(5);
        $ville->setLocale('ru')->setNom('Agadir (ru)');
        $em->persist($ville);
        $em->flush();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $translations = $repository->findTranslations($ville);
        dump($translations);
        die;

        return $this->render('example/index.html.twig', [
            'page_title' => 'WELCOME FROM ' . $ville->getNom() . ' (' . $ville->getId() . ')',
        ]);
    }
}
