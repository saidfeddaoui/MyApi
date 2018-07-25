<?php

namespace App\Controller\BackOffice;

use App\Entity\AssistanceRequest;
use App\Entity\InsuranceType;
use App\Entity\PreDeclaration;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route(name="backoffice_layout_")
 */
class LayoutController extends Controller
{

    const DEFAULT_THEME_COLOR = 'blue';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * LayoutController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route(name="change_mutuelle", path="/change_mutuelle/{id}", requirements={"id"="\d+"})
     *
     * @param InsuranceType $insuranceType
     * @param SessionInterface $session
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param Request $request
     * @return Response
     */
    public function changeMutuelle(InsuranceType $insuranceType, SessionInterface $session, AuthorizationCheckerInterface $authorizationChecker, Request $request)
    {
        if ($authorizationChecker->isGranted($insuranceType->getRole())) {
            $session->set('insuranceType', $insuranceType);
        }
        if ($referer = $request->headers->get('referer')) {
            return $this->redirect($referer);
        }
        return $this->redirect($this->generateUrl('pre_declarations_in_progress'));
    }
    /**
     * @param SessionInterface $session
     * @return Response
     */
    public function renderInsuranceTypesList(SessionInterface $session)
    {
        $insurances = $this->getUser()->getInsuranceTypes();
        return $this->render('layout/insurance_list.html.twig', [
            'insurances' => $insurances,
            'insuranceType' => $session->get('insuranceType')
        ]);
    }
    /**
     * @param SessionInterface $session
     * @return Response
     */
    public function renderThemeColor(SessionInterface $session)
    {
        $color = self::DEFAULT_THEME_COLOR;
        $insuranceType = $session->get('insuranceType');
        if ($insuranceType && $_color = $insuranceType->getThemeColor()) {
            $color = $_color;
        }
        return $this->render('layout/theme_color.html.twig', [
            'color' => $color
        ]);
    }
    /**
     *
     * @ParamConverter(name="insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     * @param InsuranceType $insuranceType
     * @return Response
     */
    public function renderNotifications(InsuranceType $insuranceType)
    {
        $preDeclarations = $this->em->getRepository('App:PreDeclaration')->findBy(
            ['insuranceType' => $insuranceType, 'status' => PreDeclaration::STATUS_IN_PROGRESS],
            ['createdAt' => 'DESC']
        );
        $assistanceRequests = $this->em->getRepository('App:AssistanceRequest')->findBy(
            ['insuranceType' => $insuranceType, 'status' => AssistanceRequest::STATUS_IN_PROGRESS],
            ['createdAt' => 'DESC']
        );
        return $this->render('layout/notifications.html.twig', [
            'preDeclarations' => $preDeclarations,
            'assistanceRequests' => $assistanceRequests,
        ]);
    }

}