<?php

namespace App\Controller;

use App\Entity\Attachment;
use App\Entity\Item;
use App\Entity\ItemList;
use App\Entity\Ville;
use App\Form\SliderType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\File;

class SliderController extends Controller
{

    /**
     * @Route(path="/sliders", name="list_slider", options={"expose"=true})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $form = $this->createForm(SliderType::class, new Item(), [
            'action' => $this->generateUrl('add_slider'),
        ]);
        $em = $this->getDoctrine()->getManager();
        /**
         * @var ItemList $sliderList
         */
        $sliderList = $em->getRepository('App:ItemList')->findOneByType('slider');
        return $this->render('slider/index.html.twig', [
            'page_title' => 'Slider',
            'page_subtitle' => '',
            'items' => $sliderList ? $sliderList->getItems() : [],
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route(path="/sliders/add", name="add_slider", options={"expose"=true})
     *
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        $form = $this->createForm(SliderType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Item $slider
             */
            $slider = $form->getData();
            /**
             * @var UploadedFile $_img
             */
            $_img = $form->get('_img')->getData();
            $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
            if ($_img) {
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $slider->setImage(new Attachment($imageFile->getBasename()));
            }
            /**
             * @var ItemList $sliderList
             */
            $sliderList = $em->getRepository('App:ItemList')->findOneByType('slider');
            $sliderList->addItem($slider);
            $em->persist($sliderList);
            $em->flush();
        }
        if ($errors = $form->getErrors()) {
            foreach ($errors as $error) {
                $this->addFlash('add.slider.error', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('list_slider'));
    }
    /**
     * @Route(path="/sliders/edit/{id}", name="edit_slider", options={"expose"=true})
     *
     * @param Item $slider
     * @param Request $request
     * @return Response
     */
    public function edit(Item $slider, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SliderType::class, $slider, [
            'action' => $this->generateUrl('edit_slider', ['id' => $slider->getId()])
        ]);
        $form->handleRequest($request);
        $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Item $submittedSlider
             */
            $submittedSlider = $form->getData();
            /**
             * @var UploadedFile $_img
             */
            $_img = $form->get('_img')->getData();
            if($_img) {
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $slider->setImage(new Attachment($imageFile->getBasename()));
            }
            $slider->setTitle($submittedSlider->getTitle());
            $em->persist($slider);
            $em->flush();
            return  $this->redirect($this->generateUrl('list_slider'));
        }
        return  $this->render('slider/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route(path="/sliders/delete/{id}", name="delete_slider", options={"expose"=true})
     *
     * @param Item $slider
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Item $slider, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($slider);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Slider supprimée avec succès"
        ));
    }

}
