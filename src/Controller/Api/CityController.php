<?php

namespace App\Controller\Api;

use App\Entity\Client;
use App\Entity\Contrats;
use App\DTO\Api\ApiResponse;
use App\Services\CitiesApiService;
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
 * @Rest\Route(path="/public/cities", name="api_cities_")
 */
class CityController extends BaseController
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
     *  tags={"Villes"},
     *  description="Infos Pratiques",
     *  @SWG\Parameter(
     *         name="IdCompteMobile ",
     *         in="formData",
     *         type="text",
     *         required=true,
     *         description="id compte client",
     *  ),
     *  @SWG\Parameter(
     *         name="IdPreDeclaration ",
     *         in="formData",
     *         type="text",
     *         required=true,
     *         description="id predeclaration",
     *  ),
     *  @SWG\Parameter(
     *         name="IdTypeDoc",
     *         in="formData",
     *         type="text",
     *         required=true,
     *         description="Id Type Doc ",
     *  ),@SWG\Parameter(
     *         name="CodeTypeDoc",
     *         in="formData",
     *         type="text",
     *         required=true,
     *         description="Code Type Doc ",
     *  ),
     *
     *  @SWG\Response(
     *         response=200,
     *         description="cities successfully listed"
     *     )
     *  )
     * @Rest\Post(path="/",name="list")
     * @param  Request $request
     * @param CitiesApiService $citiesApiService
     * @return JsonResponse
     */
    public function getCities(Request $request, CitiesApiService $citiesApiService)
    {
        $params=json_decode($request->getContent());
        $cities = $citiesApiService->getCities($params);
        //return  new JsonResponse($cities, 200);
        return $this->respondWith($cities);
    }






}