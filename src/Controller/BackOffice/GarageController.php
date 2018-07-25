<?php

namespace App\Controller\BackOffice;

use App\Entity\Garage;
use App\Form\ChargementGarageFileType;
use App\Form\GarageType;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
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
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Gestion Contenu")
 * @Breadcrumb(title="Localisation")
 */
class GarageController extends Controller
{
    /**
     * @Route("/garages", name="garage", options={"expose"=true})
     *
     * @Breadcrumb(title="Garages")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(GarageType::class, new Garage(),[
            'action' => $this->generateUrl('content_types_add_garage'),
        ]);
        $load_form = $this->createForm(ChargementGarageFileType::class, new Garage(),[
            'action' => $this->generateUrl('content_types_load_garages'),
        ]);
        $garages =  $em->getRepository('App:Garage')->findAll();
        $garages = $em->getRepository('App:Garage')->findBy(['insuranceType'=> $session->get('insuranceType')]);
        return $this->render('garage/index.html.twig', [
            'form_load' => $load_form->createView(),
            'form' => $form->createView(),
            'page_title' => 'Chargement des garages',
            'page_subtitle' => 'Garages',
            'garages'=> $garages
        ]);
    }

    /**
     * @Route(path="/garage/add", name="add_garage", options={"expose"=true})
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function add(Request $request, SessionInterface $session){

        $em = $this->getDoctrine()->getManager();
        $insuranceType = $em->getRepository('App:InsuranceType')->find($session->get('insuranceType')->getId());
        $garage = new Garage();
        $form = $this->createForm(GarageType::class, $garage);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid()) {
            $garage->setInsuranceType($insuranceType);
            $em->persist($garage);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', "Garage ajouté avec succès");
        }
        if ($errors = $form->getErrors(true)) {
            foreach ($errors as $error) {
                $this->addFlash('warning', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('content_types_garage'));
    }

    /**
     * @Route(path="/garage/load", name="load_garages", options={"expose"=true})
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function loadData(Request $request, SessionInterface $session){

        $em = $this->getDoctrine()->getManager();
        $uploadDirData = $this->get('kernel')->getProjectDir() . '/public/LoadedFiles/Garage/';
        $insuranceType = $em->getRepository('App:InsuranceType')->find($session->get('insuranceType')->getId());
        $form = $this->createForm(ChargementGarageFileType::class);
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
                $em->getRepository('App:Garage')->deleteFromTable();
                for($i=1; $i<sizeof($sheetData); $i++) {

                    $raisonSociale = $sheetData[$i][0] == "NULL" ? NULL : $sheetData[$i][0];
                    $adresse       = $sheetData[$i][2] == "NULL" ? NULL : $sheetData[$i][2];
                    $nom_ville     = $sheetData[$i][4] == "NULL" ? NULL : $sheetData[$i][4];
                    $tel           = $sheetData[$i][5] == "NULL" ? NULL : $sheetData[$i][5];
                    $responsable   = $sheetData[$i][9] == "NULL" ? NULL : $sheetData[$i][9];
                    if( is_null($raisonSociale) or is_null($adresse) ){ continue; }
                    $garage = New Garage();
                    $garage->setRaisonSociale($raisonSociale);
                    $garage->setAdresse($adresse);
                    $garage->setNomVille($nom_ville);
                    $garage->setTel($tel);
                    $garage->setResponsable($responsable);
                    $garage->setInsuranceType($insuranceType);
                    $em->persist($garage);

                }
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', "Données garage cahrgées avec succès");
        }
        return  $this->redirect($this->generateUrl('content_types_garage'));

    }

    /**
     * @Route(path="/garage/edit/{id}", name="edit_garage", options={"expose"=true})
     *
     * @param Garage $garage
     * @param Request $request
     * @return Response
     */
    public function edit(Garage $garage, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $form = $this->createForm(GarageType::class, $garage, [
            'action' => $this->generateUrl('content_types_edit_garage', ['id' => $garage->getId()])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($garage);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', "garage modifié avec succès");
            return  $this->redirect($this->generateUrl('content_types_garage'));
        }elseif($errors = $form->getErrors(true)) {
            foreach ($errors as $error) {
                $this->addFlash('warning', $error->getMessage());
                break;
            }
        }
        return  $this->render('garage/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/garage/delete/{id}", name="delete_garage", options={"expose"=true})
     *
     * @param Garage $garage
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Garage $garage, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($garage);
        $em->flush();
        return new JsonResponse(array(
            "message" => "garage supprimé avec succès"
        ));
    }

}
