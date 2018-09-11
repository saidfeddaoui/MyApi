<?php

namespace App\Controller\BackOffice;

use App\Entity\InsuranceType;
use App\Entity\PreDeclaration;
use App\Event\AcceptPreDeclarationEvent;
use App\Event\ApplicationEvents;
use App\Event\RejectPreDeclarationEvent;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/pre_declarations", name="pre_declarations_")
 * @Security("is_granted('ROLE_PRE_DECLARATION')")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Pré-déclarations")
 */
class PreDeclarationController extends Controller
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
     * PreDeclarationController constructor.
     *
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }
    /**
     * @Route(path="/in_progress", name="in_progress", options={"expose"=true})
     * @ParamConverter(name="insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @Breadcrumb(title="En cours")
     *
     * @param InsuranceType $insuranceType
     * @return Response
     */
    public function in_progress(InsuranceType $insuranceType)
    {
        $preDeclarations = $this->em->getRepository('App:PreDeclaration')->findByInsuranceTypeOrderByPredeclaration($insuranceType);
        return $this->render('pre_declaration/index.html.twig', [
            'page_title' => 'Gestion des pré-déclarations',
            'page_subtitle' => '(En cours)',
            'portlet_title' => 'Liste des pré-déclarations en cours',
            'preDeclarations' => $preDeclarations,
        ]);
    }
    /**
     * @Route(path="/display/details/{id}", name="display_details", requirements={"id":"\d+"}, options={"expose"=true})
     * @ParamConverter(name="insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @Breadcrumb(title="{preDeclaration.contrat.client.firstName}")
     *
     * @param  PreDeclaration $preDeclaration
     * @return Response
     */
    public function displayDetails(PreDeclaration $preDeclaration)
    {

        $attachements = $this->em->getRepository('App:TiersAttachment')->findByPreDeclaration($preDeclaration);


        return $this->render('pre_declaration/display_details.html.twig', [
            'page_title' => 'Gestion des pré-déclarations',
            'page_subtitle' => '',
            'portlet_title' => "Pré-déclaration de {$preDeclaration->getContrat()->getClient()->getFirstName()}",
            'preDeclaration' => $preDeclaration,
            'attachements' => $attachements,
        ]);
    }
    /**
     * @Route(path="/rejected", name="rejected", options={"expose"=true})
     * @ParamConverter(name="insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @Breadcrumb(title="Rejetées")
     *
     * @param InsuranceType $insuranceType
     * @return Response
     */
    public function rejected(InsuranceType $insuranceType)
    {
        $preDeclarations = $this->em->getRepository('App:PreDeclaration')->findByStatusAndInsuranceType(
            PreDeclaration::STATUS_REJECTED,
            $insuranceType
        );
        return $this->render('pre_declaration/index.html.twig', [
            'page_title' => 'Gestion des pré-déclarations',
            'page_subtitle' => '(Rejetées)',
            'portlet_title' => 'Liste des pré-déclarations rejetées',
            'preDeclarations' => $preDeclarations,
        ]);
    }
    /**
     * @Route(path="/accepted", name="accepted", options={"expose"=true})
     * @ParamConverter(name="insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @Breadcrumb(title="Acceptées")
     *
     * @param InsuranceType $insuranceType
     * @return Response
     */
    public function accepted(InsuranceType $insuranceType)
    {
        $preDeclarations = $this->em->getRepository('App:PreDeclaration')->findByStatusAndInsuranceType(
            PreDeclaration::STATUS_ACCEPTED,
            $insuranceType
        );
        return $this->render('pre_declaration/index.html.twig', [
            'page_title' => 'Gestion des pré-déclarations',
            'page_subtitle' => '(Acceptées)',
            'portlet_title' => 'Liste des pré-déclarations acceptées',
            'preDeclarations' => $preDeclarations,
        ]);
    }
    /**
     * @Route(path="/details/{id}", name="details", requirements={"id":"\d+"}, options={"expose"=true})
     *
     * @param  PreDeclaration $preDeclaration
     * @return Response
     */
    public function details(PreDeclaration $preDeclaration)
    {

        $attachements = $this->em->getRepository('App:TiersAttachment')->findByPreDeclaration($preDeclaration);

        return $this->render('pre_declaration/details.html.twig', [
            'preDeclaration' => $preDeclaration,
            'attachements' => $attachements,
        ]);
    }
    /**
     * @Route(path="/reject/{id}", name="reject", requirements={"id":"\d+"}, options={"expose"=true})
     *
     * @param  PreDeclaration $preDeclaration
     * @param  Request $request
     * @return Response
     */
    public function reject(PreDeclaration $preDeclaration, Request $request)
    {
        if (PreDeclaration::STATUS_IN_PROGRESS !== $preDeclaration->getStatus()) {
            return $this->json(['message' => 'la pré-declaration doit avoir le status en cours pour la rejeter'], 400);
        }
        if (!$request->request->has('description')) {
            return $this->json(['message' => 'la description est obligatoire pour rejeter une pré-declaration'], 400);
        }
        $preDeclaration
            ->setStatus(PreDeclaration::STATUS_REJECTED)
            ->setDescription($request->request->get('description'))
        ;
        $this->em->persist($preDeclaration);
        $this->em->flush();
        $event = new RejectPreDeclarationEvent($preDeclaration);
        $this->eventDispatcher->dispatch(ApplicationEvents::REJECT_PRE_DECLARATION, $event);
        return $this->json(['message' => 'la pré-declaration a été rejetée avec succès']);
    }
    /**
     * @Route(path="/accept/{id}", name="accept", requirements={"id":"\d+"}, options={"expose"=true})
     *
     * @param  PreDeclaration $preDeclaration
     * @return Response
     */
    public function accept(PreDeclaration $preDeclaration)
    {
        if (PreDeclaration::STATUS_IN_PROGRESS !== $preDeclaration->getStatus()) {
            return $this->json(['message' => 'la pré-declaration doit avoir le status en cours pour l\'accepter'], 400);
        }
        $preDeclaration->setStatus(PreDeclaration::STATUS_ACCEPTED);
        $this->em->persist($preDeclaration);
        $this->em->flush();
        $event = new AcceptPreDeclarationEvent($preDeclaration);
        $this->eventDispatcher->dispatch(ApplicationEvents::ACCEPT_PRE_DECLARATION, $event);
        return $this->json(['message' => 'la pré-declaration a été acceptée avec succès']);
    }

}