<?php

namespace App\Controller\BackOffice;

use App\Entity\Attachment;
use App\Entity\Item;
use App\Entity\ItemList;
use App\Form\SliderType;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route(path="/content_types", name="content_types_")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Gestion Contenu")
 */
class SliderController extends Controller
{

    /**
     * @Route(path="/slider", name="list_slider", options={"expose"=true})
     *
     * @Breadcrumb(title="Slider")
     *
     * @param SessionInterface $session
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(SliderType::class, new Item(), [
            'action' => $this->generateUrl('content_types_add_slider'),
        ]);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $data = array();
        /**
         * @var ItemList $sliderList
         */
        $sliderList = $em->getRepository('App:ItemList')->findOneBy(['type'=>'slider','insuranceType'=> $session->get('insuranceType')]);
        foreach ($sliderList->getItems() as $key => $value){
            $translations =  $repository->findTranslations($value);
            $data[] = array(
                'id' => $value->getId(),
                'title' => $value->getTitle(),
                'image' => $value->getImage(),
                'title_ar' => $translations['ar']["title"] ?? '',
            );
        }

        return $this->render('slider/index.html.twig', [
            'page_title' => 'Slider',
            'page_subtitle' => '',
            'items' => $data ? $data : [],
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route(path="/slider/add", name="add_slider", options={"expose"=true})
     * @param SessionInterface $session
     * @param Request $request
     * @return Response
     */
    public function add(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(SliderType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $insuranceType = $em->getRepository('App:InsuranceType')->find($session->get('insuranceType')->getId());
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Item $slider
             */
            $slider = $form->getData();
            /**
             * @var UploadedFile $_img
             */
            $_img = $form->get('_img')->getData();
            $iName_ar = $form->get('title_ar')->getData();
            $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
            if ($_img) {
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $slider->setImage(new Attachment($imageFile->getBasename()));
            }
            /**
             * @var ItemList $sliderList
             */
            $sliderList = $em->getRepository('App:ItemList')->findOneBy(['type'=>'slider','insuranceType'=> $insuranceType]);

            $sliderList->addItem($slider);
            $em->persist($sliderList);
            $repository->translate($slider, 'title', 'ar', $iName_ar) ;
            $em->flush();
        }
        if ($errors = $form->getErrors()) {
            foreach ($errors as $error) {
                $this->addFlash('add.slider.error', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('content_types_list_slider'));
    }
    /**
     * @Route(path="/slider/edit/{id}", name="edit_slider", options={"expose"=true})
     *
     * @param Item $slider
     * @param Request $request
     * @return Response
     */
    public function edit(Item $slider, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $form = $this->createForm(SliderType::class, $slider, [
            'action' => $this->generateUrl('content_types_edit_slider', ['id' => $slider->getId()])
        ]);
        $translations =  $repository->findTranslations($slider);
        if($translations){
            $form->get('title_ar')->setData($translations['ar']["title"]);
        }
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
            $iName_ar = $form->get('title_ar')->getData();
            if($_img) {
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $slider->setImage(new Attachment($imageFile->getBasename()));
            }
            $slider->setTitle($submittedSlider->getTitle());
            $repository->translate($slider, 'title', 'ar', $iName_ar) ;
            $em->persist($slider);
            $em->flush();
            return  $this->redirect($this->generateUrl('content_types_list_slider'));
        }
        return  $this->render('slider/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route(path="/slider/delete/{id}", name="delete_slider", options={"expose"=true})
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
