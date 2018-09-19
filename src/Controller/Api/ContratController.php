<?php

namespace App\Controller\Api;

use App\Entity\Client;
use App\Entity\Contrats;
use App\DTO\Api\ApiResponse;
use App\Services\ContratApiService;
use App\Services\DetailsContratApiService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse ;
use Symfony\Component\HttpFoundation\Request;

use Swagger\Annotations as SWG;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Rest\Route(path="/public/contrat", name="api_contrat_")
 */
class ContratController extends BaseController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     *
     * @SWG\Post(
     *  tags={"Contrat"},
     *
     *  @SWG\Response(
     *         response=200,
     *         description="contrat successfully added"
     *     )
     *  )
     * @Rest\Post(path="/add",name="add")
     * @param  Request $request
     * @param ContratApiService $contratApiService
     * @return JsonResponse
     */
    public function AddContrat(Request $request, ContratApiService $contratApiService)
    {
        $contrat=json_decode($request->getContent());
        $contra = $contratApiService->AddContrat($contrat);
        return  new JsonResponse($contra, 200);
        // return $this->respondWith($contrat);
    }

    /**
     *
     * @SWG\Post(
     *  tags={"Contrat"},
     *
     *  @SWG\Response(
     *      response=200,
     *      description="contrat successfully returned"
     *   )
     *  )
     * @Rest\Post(path="/details",name="details")
     * @param  Request $request
     * @param DetailsContratApiService $detailsContratApiService
     * @return JsonResponse
     */
    public function detailsContrat(Request $request, DetailsContratApiService $detailsContratApiService)
    {
        $contrat=json_decode($request->getContent());
        $contra = $detailsContratApiService->detailsContrat($contrat);
        return  new JsonResponse($contra, 200);
    }

    /**
     *
     * @SWG\Get(
     *  tags={"Contrat"},
     *  @SWG\Response(
     *      response=200,
     *      description="contrat successfully returned"
     *   )
     *  )
     *
     * @Rest\Get(path = "/{id}", name = "contrat")
     * @Rest\View(serializerGroups={"all", "contrats"})
     * @param Client $id
     * @return ApiResponse
     */
    public function getContrat($id)
    {
        $contrat = $this->em->getRepository('App:Contrats')->findBy([ 'client' => $id,"actif"=>true]);
        return $this->respondWith($contrat);
    }


    /**
     *
     * @SWG\Delete(
     *  tags={"Contrat"},
     *
     *  @SWG\Response(
     *         response=200,
     *         description="contrat successfully deleted"
     *     )
     *  )
     *
     * @Rest\Delete(path = "/delete/{police}", name = "delete")
     * @Rest\View(serializerGroups={"all", "contrats"})
     * @param Contrats $police
     * @return JsonResponse
     */
    public function deleteContrat(Contrats $police)
    {
        $this->em->remove($police);
        $this->em->flush();
        return new JsonResponse(array(
            "code"=>200,
            "status"=>"ok",
            "message"=>"le contrat a été supprimé avec succès"
        ));
    }

    /**
     *
     * @SWG\Post(
     *  tags={"Contrat"},
     *  @SWG\Response(
     *         response=200,
     *         description="contrats successfully deleted"
     *     )
     *  )
     *
     * @Rest\Post(path="/delete/many",name="delete_many")
     * @param  Request $request
     * @param ContratApiService $contratApiService
     * @return JsonResponse
     */
    public function deleteManyContrat(Request $request)
    {


        $contrats=json_decode($request->getContent());

        return new JsonResponse(array(
            "code"=>200,
            "status"=>"ok",
            "message"=>$contrats
        ));
        

        foreach ($contrats as $contrat) {
            $contra = $this->em->getRepository("App:Contrats")->find($contrat->id);
            $contra->setActif(false);
            $datenow = new \DateTime("now");
            $contra->setDateSuppression($datenow);
        }

        $this->em->flush();
        return new JsonResponse(array(
            "code"=>200,
            "status"=>"ok",
            "message"=>"les contrat ont été supprimé avec succès"
        ));

    }





}