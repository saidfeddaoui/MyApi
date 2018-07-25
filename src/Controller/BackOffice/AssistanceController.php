<?php

namespace App\Controller\BackOffice;

use App\Entity\AssistanceRequest;
use App\Entity\InsuranceType;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route(path="/assistance", name="assistance_")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Demandes d'assistance")
 */
class AssistanceController extends Controller
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * AssistanceController constructor.
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/in_progress", name="in_progress")
     * @ParamConverter(name="insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @Breadcrumb(title="En cours")
     *
     * @param InsuranceType $insuranceType
     * @return Response
     */
    public function in_progress(InsuranceType $insuranceType)
    {
        $assistanceRequests = $this->em->getRepository('App:AssistanceRequest')->findBy(
            ['insuranceType' => $insuranceType, 'status' => AssistanceRequest::STATUS_IN_PROGRESS],
            ['createdAt' => 'DESC']
        );
        return $this->render('assistance/index.html.twig', [
            'page_title' => 'Gestion des demandes d\'assistance',
            'page_subtitle' => '(En cours)',
            'portlet_title' => 'Liste des demandes d\'assistance en cours',
            'assistanceRequests' => $assistanceRequests,
        ]);
    }
    /**
     * @Route("/handled", name="handled")
     * @ParamConverter(name="insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @Breadcrumb(title="Traitées")
     *
     * @param InsuranceType $insuranceType
     * @return Response
     */
    public function handled(InsuranceType $insuranceType)
    {
        $assistanceRequests = $this->em->getRepository('App:AssistanceRequest')->findBy(
            ['insuranceType' => $insuranceType, 'status' => AssistanceRequest::STATUS_HANDLED],
            ['createdAt' => 'DESC']
        );
        return $this->render('assistance/index.html.twig', [
            'page_title' => 'Gestion des demandes d\'assistance',
            'page_subtitle' => '(Traitées)',
            'portlet_title' => 'Liste des demandes d\'assistance traitées',
            'assistanceRequests' => $assistanceRequests,
        ]);
    }
    /**
     * @Route(path="/handle/{id}", name="handle", requirements={"id":"\d+"}, options={"expose"=true})
     *
     * @param  AssistanceRequest $assistanceRequest
     * @return Response
     */
    public function handle(AssistanceRequest $assistanceRequest)
    {
        if (AssistanceRequest::STATUS_IN_PROGRESS !== $assistanceRequest->getStatus()) {
            return $this->json(['message' => 'la pré-declaration doit avoir le status en cours pour la traitée'], 400);
        }
        $assistanceRequest->setStatus(AssistanceRequest::STATUS_HANDLED);
        $this->em->persist($assistanceRequest);
        $this->em->flush();
        return $this->json(['message' => 'la demande a été bien traitée']);
    }
    /**
     * @Route(path="/display/{id}", name="display", requirements={"id":"\d+"}, options={"expose"=true})
     *
     * @Breadcrumb(title="{assistanceRequest.personalInformation.name}")
     *
     * @param AssistanceRequest $assistanceRequest
     * @return Response
     */
    public function display(AssistanceRequest $assistanceRequest)
    {
        return $this->render('assistance/index.html.twig', [
            'page_title' => 'Gestion des demandes d\'assistance',
            'page_subtitle' => '',
            'portlet_title' => 'Demande d\'assistance',
            'assistanceRequests' => [ $assistanceRequest ],
        ]);
    }

}
