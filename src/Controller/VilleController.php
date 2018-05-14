<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class VilleController extends Controller
{
    /**
     * @Route("/ville", name="ville")
     */
    public function index(Request $request)
    {
        return $this->render('ville/index.html.twig', [
            'page_title' => 'Villes de réparation',
            'page_subtitle' => '',
        ]);
        $ville = new Ville();
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ville = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $nom_ar = $form->get('nom_ar')->getData();
            $em->persist($ville);
            $em->flush();
            if ($nom_ar) {
                $ville->setLocale('ar')->setNom($nom_ar);
                $em->flush();
            }
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();
            //return $this->redirectToRoute('task_success');
        }
        return $this->render('ville/index.html.twig', [
            'title' => 'Villes',
            'form' => $form->createView(),
        ]);
    }
}
