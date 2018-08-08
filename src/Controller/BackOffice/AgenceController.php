<?php

namespace App\Controller\BackOffice;

use App\Entity\Agence;
use App\Form\AgenceType;
use App\Form\ChargementAgenceFileType;
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
class AgenceController extends Controller
{
    /**
     * @Route("/agences", name="agences", options={"expose"=true})
     *
     * @Breadcrumb(title="Agences")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AgenceType::class, new Agence(),[
            'action' => $this->generateUrl('content_types_add_agence'),
        ]);
        $load_form = $this->createForm(ChargementAgenceFileType::class, new Agence(),[
            'action' => $this->generateUrl('content_types_load_agences'),
        ]);
        $agences = $em->getRepository('App:Agence')->findBy(['insuranceType'=> $session->get('insuranceType')]);
        return $this->render('agence/index.html.twig', [
            'form_load' => $load_form->createView(),
            'form' => $form->createView(),
            'page_title' => 'Chargement des agences',
            'page_subtitle' => 'Agences',
            'agences'=> $agences
        ]);
    }

    /**
     * @Route(path="/agence/add", name="add_agence", options={"expose"=true})
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function add(Request $request, SessionInterface $session){

        $em = $this->getDoctrine()->getManager();
        $insuranceType = $em->getRepository('App:InsuranceType')->find($session->get('insuranceType')->getId());
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid()) {
            $agence->setInsuranceType($insuranceType);
            $em->persist($agence);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', "Agence ajoutée avec succès");
        }
        if ($errors = $form->getErrors(true)) {
            foreach ($errors as $error) {
                $this->addFlash('warning', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('content_types_agences'));
    }

    /**
     * @Route(path="/agence/load", name="load_agences", options={"expose"=true})
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function loadData(Request $request, SessionInterface $session){

        $em = $this->getDoctrine()->getManager();
        $uploadDirData = $this->get('kernel')->getProjectDir() . '/public/LoadedFiles/Agences/';
        $insuranceType = $em->getRepository('App:InsuranceType')->find($session->get('insuranceType')->getId());
        $form = $this->createForm(ChargementAgenceFileType::class);
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
                $em->getRepository('App:Agence')->deleteFromTable($insuranceType);
                for($i=1; $i<sizeof($sheetData); $i++) {

                    $raisonSociale = $sheetData[$i][0] == "NULL" ? NULL : $sheetData[$i][0];
                    $adresse       = $sheetData[$i][1] == "NULL" ? NULL : $sheetData[$i][1];
                    $ville         = $sheetData[$i][2] == "NULL" ? NULL : $sheetData[$i][2];

                    if( is_null($raisonSociale) or is_null($adresse) ){ continue; }
                    $agence = New Agence();
                    $agence->setRaisonSociale($raisonSociale);
                    $agence->setAdresse($adresse);
                    $agence->setNomVille($ville);
                    $agence->setInsuranceType($insuranceType);
                    $em->persist($agence);

                }
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', "Données agence cahrgées avec succès");
        }
        return  $this->redirect($this->generateUrl('content_types_agences'));

    }

    /**
     * @Route(path="/agence/edit/{id}", name="edit_agence", options={"expose"=true})
     *
     * @param Agence $agence
     * @param Request $request
     * @return Response
     */
    public function edit(Agence $agence, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $form = $this->createForm(AgenceType::class, $agence, [
            'action' => $this->generateUrl('content_types_edit_agence', ['id' => $agence->getId()])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($agence);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', "agence modifiée avec succès");
            return  $this->redirect($this->generateUrl('content_types_agences'));
        }
        return  $this->render('agence/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/agence/delete/{id}", name="delete_agence", options={"expose"=true})
     *
     * @param Agence $agence
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Agence $agence, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($agence);
        $em->flush();
        return new JsonResponse(array(
            "message" => "agence supprimée avec succès"
        ));
    }

}
