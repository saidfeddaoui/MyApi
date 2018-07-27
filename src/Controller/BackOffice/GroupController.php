<?php

namespace App\Controller\BackOffice;

use App\Entity\Group;
use App\Form\GroupType;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route(path="/administration", name="administration_")
 * @Security("is_granted('ROLE_GROUPS_MANAGEMENT')")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Administration")
 */
class GroupController extends Controller
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
     * @Route(path="/groups", name="list_group", options={"expose"=true})
     *
     * @Breadcrumb(title="Gestion des groupes")
     */
    public function index()
    {
        $groups = $this->em->getRepository('App:Group')->findAll();
        $form = $this->createForm(GroupType::class, new Group(), [
            'action' => $this->generateUrl('administration_add_group'),
        ]);
        return $this->render('group/index.html.twig', [
            'page_title' => 'Gestion des groupes',
            'page_subtitle' => '',
            'portlet_title' => 'Liste des groupes',
            'form' => $form->createView(),
            'groups' => $groups,
        ]);
    }
    /**
     * @Route(path="/groups/add", name="add_group", options={"expose"=true})
     *
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $group->setRole(strtoupper(preg_replace('#\s+#', '_', $group->getRole())));
            if (!preg_match('#^ROLE_#', $group->getRole())) {
                $group->setRole('ROLE_' . $group->getRole());
            }
            $this->em->persist($group);
            foreach ($group->getChildren() as $child) {
                $child->addParent($group);
                $this->em->persist($child);
            }
            $this->em->flush();
        }
        return $this->redirectToRoute('administration_list_group');
    }
    /**
     * @Route("/groups/edit/{id}", name="edit_group",options={"expose"=true})
     *
     * @param Group $group
     * @param Request $request
     * @return Response
     */
    public function edit(Group $group, Request $request)
    {
        $persistedChildren = clone $group->getChildren();
        $form = $this->createForm(GroupType::class, $group, [
            'action' => $this->generateUrl('administration_edit_group', ['id' => $group->getId()])
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $group->setRole(strtoupper(preg_replace('#\s+#', '_', $group->getRole())));
            if (!preg_match('#^ROLE_#', $group->getRole())) {
                $group->setRole('ROLE_' . $group->getRole());
            }
            $this->em->persist($group);
            foreach ($persistedChildren as $persistedChild) {
                if ($group->getChildren()->contains($persistedChildren)) {
                    continue;
                }
                $persistedChild->removeParent($group);
                $this->em->persist($persistedChild);
            }
            foreach ($group->getChildren() as $child) {
                $child->addParent($group);
                $this->em->persist($child);
            }
            $this->em->flush();
            return $this->redirectToRoute('administration_list_group');
        }
        return $this->render('group/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/groups/delete/{id}", name="delete_group", options={"expose"=true})
     *
     * @param Group $group
     * @return Response
     */
    public function delete(Group $group)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($group);
        $em->flush();
        return $this->json(['message' => 'Le groupe a été supprimé avec succès']);
    }

}
