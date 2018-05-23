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
     * @Route(path="/sliders", name="list_sliders", options={"expose"=true})
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
        $sliderList = $em->getRepository('App:ItemList')->findOneByType('sliders');
        return $this->render('slider/index.html.twig', [
            'page_title' => 'Sliders',
            'page_subtitle' => '',
            'sliders' => $sliderList->getItems(),
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route(path="/sliders/add", name="add_slider", options={"expose"=true})
     *
     * @param Request $request
     * @return Response
     */
    public function addSlider(Request $request)
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
            $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());

            $slider
                ->setImage(new Attachment($imageFile->getBasename()))
            ;
            /**
             * @var ItemList $sliderList
             */
            $sliderList = $em->getRepository('App:ItemList')->findOneByType('sliders');
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
        return  $this->redirect($this->generateUrl('list_sliders'));
    }
    /**
     * @Route(path="/sliders/edit/{id}", name="edit_slider", options={"expose"=true})
     *
     * @param Item $slider
     * @param Request $request
     * @return JsonResponse
     */
    public function editSlider(Item $slider, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SliderType::class, $slider,[
            'action' => $this->generateUrl('edit_slider',array('id' => $slider->getId()))]);
        $form->handleRequest($request);
        $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Item $slider
             */
            $slider = $form->getData();
            /**
             * @var UploadedFile $_img
             */
            $_img = $form->get('_img')->getData();

            if($_img != null){
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $slider
                 ->setImage(new Attachment($imageFile->getBasename()));
            }
            ;

            $slider
                ->setTitle($form->get('title')->getData());
            $em->persist($slider);
            $em->flush();
            return  $this->redirect($this->generateUrl('list_sliders'));
        }
        return  $this->render('slider/edit.html.twig',array(
            'form'=>$form->createView() ));
    }
    /**
     * @Route(path="/sliders/delete/{id}", name="delete_slider", options={"expose"=true})
     *
     * @param Item $slider
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteSlider(Item $slider, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($slider);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Slider supprimée avec succès"
        ));
    }
}
