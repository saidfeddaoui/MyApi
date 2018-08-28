<?php

namespace App\Controller\BackOffice;

use App\Entity\Attachment;
use App\Entity\Item;
use App\Entity\ItemList;
use App\Form\AboutType;
use App\Form\SliderType;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
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
class AboutController extends Controller
{

    /**
     * @Route(path="/about", name="about", options={"expose"=true})
     *
     * @Breadcrumb(title="Qui Sommes Nous")
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
        $sliderList = $em->getRepository('App:ItemList')->findOneBy(['type'=>'about','insuranceType'=> $session->get('insuranceType')]);
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
     * @Route(path="/about/add", name="add_about", options={"expose"=true})
     * @param SessionInterface $session
     * @param Request $request
     * @return Response
     */
    public function add(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(AboutType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $insuranceType = $em->getRepository('App:InsuranceType')->find($session->get('insuranceType')->getId());
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Item $about
             */
            $about = $form->getData();
            /**
             * @var UploadedFile $_img
             */
            $_img = $form->get('_img')->getData();
            $iName_ar = $form->get('title_ar')->getData();
            $iContent_ar = $form->get('content_ar')->getData();
            $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
            if ($_img) {
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $about->setImage(new Attachment($imageFile->getBasename()));
            }
            /**
             * @var ItemList $abouts
             */
            $abouts = $em->getRepository('App:ItemList')->findOneBy(['type'=>'about','insuranceType'=> $insuranceType]);

            $abouts->addItem($about);
            $em->persist($abouts);
            $repository->translate($about, 'title', 'ar', $iName_ar) ;
            $repository->translate($about, 'content', 'ar', $iContent_ar) ;
            $em->flush();
        }
        if ($errors = $form->getErrors()) {
            foreach ($errors as $error) {
                $this->addFlash('add.slider.error', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('content_types_about'));
    }
    /**
     * @Route(path="/slider/edit/{id}", name="edit_about", options={"expose"=true})
     *
     * @param Item $about
     * @param Request $request
     * @return Response
     */
    public function edit(Item $about, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $form = $this->createForm(SliderType::class, $about, [
            'action' => $this->generateUrl('content_types_edit_about', ['id' => $about->getId()])
        ]);
        $translations =  $repository->findTranslations($about);
        if($translations){
            $form->get('title_ar')->setData($translations['ar']["title"]);
            $form->get('content_ar')->setData($translations['ar']["content"]);
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
            $iContent_ar = $form->get('content_ar')->getData();
            if($_img) {
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $about->setImage(new Attachment($imageFile->getBasename()));
            }
            $about->setTitle($submittedSlider->getTitle());
            $repository->translate($about, 'title', 'ar', $iName_ar) ;
            $repository->translate($about,'content', 'ar', $iContent_ar) ;
            $em->persist($about);
            $em->flush();
            return  $this->redirect($this->generateUrl('content_types_about'));
        }
        return  $this->render('About/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route(path="/about/delete/{id}", name="delete_about", options={"expose"=true})
     *
     * @param Item $about
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Item $about, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($about);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Contenu supprimée avec succès"
        ));
    }

}
