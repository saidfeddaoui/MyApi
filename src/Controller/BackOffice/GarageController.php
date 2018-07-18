<?php

namespace App\Controller\BackOffice;

use App\Entity\Garage;
use App\Form\ChargementGarageFileType;
use Gedmo\Uploadable\Uploadable;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GarageController extends Controller
{
    /**
     * @Route("/garage", name="garage")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $uploadDirData = $this->get('kernel')->getProjectDir() . '/public/LoadedFiles/Garage/';
        $form = $this->createForm(ChargementGarageFileType::class,new Garage(),[
            'action' => $this->generateUrl('garage'),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

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

                $raisonSociale = $sheetData[$i][0];
                $adresse       = $sheetData[$i][2];
                $nom_ville     = $sheetData[$i][4];
                $tel           = $sheetData[$i][5];
                $responsable   = $sheetData[$i][9];
                //if( is_null($raisonSociale) or  is_null($adresse) ){ continue; }
                $garage = New Garage();
                $garage->setRaisonSociale($raisonSociale);
                $garage->setAdresse($adresse);
                $garage->setNomVille($nom_ville);
                $garage->setTel($tel);
                $garage->setResponsable($responsable);
                $em->persist($garage);

            }

            $em->flush();
            die();

        }

        return $this->render('localisation/index.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Chargement des garages',
            'page_subtitle' => 'Garages',
        ]);
    }
}
