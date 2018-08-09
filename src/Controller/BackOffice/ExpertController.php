<?php

namespace App\Controller\BackOffice;

use App\Entity\Expert;
use App\Form\ChargementExpertFileType;
use App\Form\ExpertType;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route(path="/content_types", name="content_types_")
 * @Security("is_granted('ROLE_CONTENT_MANAGEMENT')")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Gestion Contenu")
 * @Breadcrumb(title="Localisation")
 */
class ExpertController extends Controller
{
    /**
     * @Route("/experts", name="expert", options={"expose"=true})
     *
     * @Breadcrumb(title="Experts")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ExpertType::class, new Expert(),[
            'action' => $this->generateUrl('content_types_add_expert'),
        ]);
        $load_form = $this->createForm(ChargementExpertFileType::class, new Expert(),[
            'action' => $this->generateUrl('content_types_load_experts'),
        ]);
        $experts = $em->getRepository('App:Expert')->findBy(['insuranceType'=> $session->get('insuranceType')]);
        return $this->render('expert/index.html.twig', [
            'form_load' => $load_form->createView(),
            'form' => $form->createView(),
            'page_title' => 'Chargement des experts',
            'page_subtitle' => 'Experts',
            'experts'=> $experts
        ]);
    }

    /**
     * @Route(path="/expert/add", name="add_expert", options={"expose"=true})
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function add(Request $request, SessionInterface $session){

        $em = $this->getDoctrine()->getManager();
        $insuranceType = $em->getRepository('App:InsuranceType')->find($session->get('insuranceType')->getId());
        $expert = new Expert();
        $form = $this->createForm(ExpertType::class, $expert);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid()) {
            $expert->setInsuranceType($insuranceType);
            $em->persist($expert);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', "Expert ajouté avec succès");
        }
        if ($errors = $form->getErrors(true)) {
            foreach ($errors as $error) {
                $this->addFlash('warning', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('content_types_expert'));
    }

    /**
     * @Route(path="/expert/load", name="load_experts", options={"expose"=true})
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function loadData(Request $request, SessionInterface $session){

        $em = $this->getDoctrine()->getManager();
        $uploadDirData = $this->get('kernel')->getProjectDir() . '/public/LoadedFiles/Experts/';
        $insuranceType = $em->getRepository('App:InsuranceType')->find($session->get('insuranceType')->getId());
        $form = $this->createForm(ChargementExpertFileType::class);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {

                /**
                 * @var UploadedFile $file
                 */
                $file = $form->get('file')->getData();
                $name = $file->getClientOriginalName();
                $file->move($uploadDirData,$name);
                $spreadsheet = IOFactory::load($uploadDirData.$name);
                $worksheet = $spreadsheet->getActiveSheet(null, true, true, true);
                $sheetData = $worksheet->toArray();
                $em->getRepository('App:Expert')->deleteFromTable($insuranceType);
                for($i=1; $i<sizeof($sheetData); $i++) {

                    $raisonSociale = $sheetData[$i][0] == "NULL" ? NULL : $sheetData[$i][0];
                    $adresse       = $sheetData[$i][1] == "NULL" ? NULL : $sheetData[$i][1];
                    $ville         = $sheetData[$i][2] == "NULL" ? NULL : $sheetData[$i][2];
                    $tel           = $sheetData[$i][3] == "NULL" ? NULL : $sheetData[$i][3];
                    $responsable   = $sheetData[$i][4] == "NULL" ? NULL : $sheetData[$i][4];
                    $gps           = $sheetData[$i][5] == "NULL" ? NULL : $sheetData[$i][5];

                    if( is_null($raisonSociale) or is_null($adresse) ){ continue; }
                    $expert = New Expert();
                    $expert->setRaisonSociale($raisonSociale);
                    $expert->setAdresse($adresse);
                    $expert->setTel($tel);
                    $expert->setNomVille($ville);
                    $expert->setResponsable($responsable);
                    $expert->setCoordinates($gps);
                    $expert->setInsuranceType($insuranceType);
                    $em->persist($expert);

                }
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', "Données expert cahrgées avec succès");
        }
        return  $this->redirect($this->generateUrl('content_types_expert'));

    }

    /**
     * @Route(path="/expert/edit/{id}", name="edit_expert", options={"expose"=true})
     *
     * @param Expert $expert
     * @param Request $request
     * @return Response
     */
    public function edit(Expert $expert, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $form = $this->createForm(ExpertType::class, $expert, [
            'action' => $this->generateUrl('content_types_edit_expert', ['id' => $expert->getId()])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($expert);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', "expert modifié avec succès");
            return  $this->redirect($this->generateUrl('content_types_expert'));
        }
        return  $this->render('expert/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/expert/delete/{id}", name="delete_expert", options={"expose"=true})
     *
     * @param Expert $expert
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Expert $expert, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($expert);
        $em->flush();
        return new JsonResponse(array(
            "message" => "expert supprimé avec succès"
        ));
    }

}
