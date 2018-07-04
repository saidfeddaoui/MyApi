<?php

namespace App\Controller\BackOffice;

use App\Entity\InsuranceType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route(name="backoffice_layout_")
 */
class LayoutController extends Controller
{

    const DEFAULT_THEME_COLOR = 'blue';

    /**
     * @Route(name="change_mutuelle", path="/change_mutuelle/{id}", requirements={"id"="\d+"})
     *
     * @param InsuranceType $insuranceType
     * @param SessionInterface $session
     * @return Response
     */
    public function changeMutuelle(InsuranceType $insuranceType, SessionInterface $session)
    {
        $session->set('insuranceType', serialize($insuranceType));
        return $this->redirect($this->generateUrl('list_ville'));
    }
    public function renderInsuranceTypesList()
    {
        $insurances = $this->getUser()->getInsuranceTypes();
        return $this->render('layout/insurance_list.html.twig', [
            'insurances' => $insurances
        ]);
    }
    public function renderThemeColor(SessionInterface $session)
    {
        $color = self::DEFAULT_THEME_COLOR;
        $insuranceType = unserialize($session->get('insuranceType'));
        if ($insuranceType && $_color = $insuranceType->getThemeColor()) {
            $color = $_color;
        }
        return $this->render('layout/theme_color.html.twig', [
            'color' => $color
        ]);
    }

}