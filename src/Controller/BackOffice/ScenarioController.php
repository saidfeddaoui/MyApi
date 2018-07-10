<?php


namespace App\Controller\BackOffice;
use App\Entity\Item;
use App\Entity\ItemList;
use App\Form\ScenarioType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ScenarioController extends Controller
{
    /**
     * @Route(path="/scenarios", name="scenarios", options={"expose"=true})
     * @param SessionInterface $session
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(ScenarioType::class, new Item(), [
            'action' => $this->generateUrl('add_scenario'),
        ]);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $data = [];
        /**
         * @var ItemList $scenariosList
         */
        $scenariosList = $em->getRepository('App:ItemList')->findOneBy(['type'=>'scenario','insuranceType'=> $session->get('insuranceType')]);
        foreach ($scenariosList->getItems() as $key => $value){
            $translations =  $repository->findTranslations($value);
            $data[] = [
                'id' => $value->getId(),
                'content' => $value->getContent(),
                'content_ar' => $translations['ar']['content'] ?? ''
            ];
        }
        return $this->render('scenario/index.html.twig', [
            'page_title' => 'Scénarios des pré-déclarations',
            'page_subtitle' => '',
            'scenarios' => $data ? $data : [],
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="/scenarios/add", name="add_scenario", options={"expose"=true})
     *
     * @param Request $request
     * @param $validator
     * @param SessionInterface $session
     * @return Response
     */
    public function add(Request $request,ValidatorInterface $validator, SessionInterface $session)
    {
        $form = $this->createForm(ScenarioType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $insuranceType = $em->getRepository('App:InsuranceType')->find($session->get('insuranceType')->getId());
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Item $scenario
             */
            $scenario = $form->getData();
            $iContent_ar = $form->get('content_ar')->getData();
            $em = $this->getDoctrine()->getManager();
            /**
             * @var ItemList $scenariosList
             */
            $scenariosList = $em->getRepository('App:ItemList')->findOneBy(['type'=>'scenario','insuranceType'=> $insuranceType]);
            $scenariosList->addItem($scenario);
            $em->persist($scenariosList);
            $repository->translate($scenario, 'content', 'ar', $iContent_ar) ;
            $em->flush();
        }
        if ($errors = $form->getErrors(true)) {
            foreach ($errors as $error) {
                $this->addFlash('warning', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('scenarios'));
    }

    /**
     * @Route(path="/scenarios/edit/{id}", name="edit_scenario", options={"expose"=true})
     *
     * @param Item $scenario
     * @param Request $request
     * @return Response
     */
    public function edit(Item $scenario, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $form = $this->createForm(ScenarioType::class, $scenario, [
            'action' => $this->generateUrl('edit_scenario', ['id' => $scenario->getId()])
        ]);
        $translations =  $repository->findTranslations($scenario);
        $form->get('content_ar')->setData($translations['ar']["content"]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $submittedMode = $form->getData();
            $scenario
                ->setTitle($submittedMode->getContent())
            ;

            $iContent_ar = $form->get('content_ar')->getData();

            $repository->translate($scenario, 'content', 'ar', $iContent_ar) ;
            $em->persist($scenario);
            $em->flush();
            return  $this->redirect($this->generateUrl('scenarios'));
        }
        return  $this->render('scenario/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route(path="/scenarios/delete/{id}", name="delete_scenario", options={"expose"=true})
     *
     * @param Item $scenario
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Item $scenario, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($scenario);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Scénario de pré-déclaration supprimé avec succès"
        ));
    }
}