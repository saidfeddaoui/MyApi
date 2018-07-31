<?php

namespace App\Controller\BackOffice;

use App\Entity\Role;
use App\Form\RoleType;
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
 * @Security("is_granted('ROLE_ROLES_MANAGEMENT')")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Administration")
 */
class RoleController extends Controller
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
     * @Route("/roles", name="list_role", options={"expose"=true})
     *
     * @Breadcrumb(title="Gestion des rôles")
     */
    public function index()
    {
        $roles = $this->em->getRepository('App:Role')->findAll();
        $form = $this->createForm(RoleType::class, new Role(), [
            'action' => $this->generateUrl('administration_add_role'),
        ]);
        return $this->render('role/index.html.twig', [
            'page_title' => 'Gestion des rôles',
            'page_subtitle' => '',
            'portlet_title' => 'Liste des rôles',
            'form' => $form->createView(),
            'roles' => $roles,
        ]);
    }
    /**
     * @Route(path="/roles/add", name="add_role", options={"expose"=true})
     *
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $role->setRole(strtoupper(preg_replace('#\s+#', '_', $role->getRole())));
            if (!preg_match('#^ROLE_#', $role->getRole())) {
                $role->setRole('ROLE_' . $role->getRole());
            }
            $this->em->persist($role);
            foreach ($role->getChildren() as $child) {
                $child->addParent($role);
                $this->em->persist($child);
            }
            $this->em->flush();
        }
        return $this->redirectToRoute('administration_list_role');
    }
    /**
     * @Route("/roles/edit/{id}", name="edit_role",options={"expose"=true})
     *
     * @param Role $role
     * @param Request $request
     * @return Response
     */
    public function edit(Role $role, Request $request)
    {
        $persistedChildren = clone $role->getChildren();
        $role->setRole(preg_replace('#^ROLE_#', '', $role->getRole()));
        $form = $this->createForm(RoleType::class, $role, [
            'action' => $this->generateUrl('administration_edit_role', ['id' => $role->getId()])
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $role->setRole(strtoupper(preg_replace('#\s+#', '_', $role->getRole())));
            if (!preg_match('#^ROLE_#', $role->getRole())) {
                $role->setRole('ROLE_' . $role->getRole());
            }
            $this->em->persist($role);
            foreach ($persistedChildren as $persistedChild) {
                if ($role->getChildren()->contains($persistedChildren)) {
                    continue;
                }
                $persistedChild->removeParent($role);
                $this->em->persist($persistedChild);
            }
            foreach ($role->getChildren() as $child) {
                $child->addParent($role);
                $this->em->persist($child);
            }
            $this->em->flush();
            return $this->redirectToRoute('administration_list_role');
        }
        return $this->render('role/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/roles/delete/{id}", name="delete_role", options={"expose"=true})
     *
     * @param Role $role
     * @return Response
     */
    public function delete(Role $role)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($role);
        $em->flush();
        return $this->json(['message' => 'Le rôle a été supprimé avec succès']);
    }

}
