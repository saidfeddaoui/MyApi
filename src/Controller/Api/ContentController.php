<?php

namespace App\Controller\Api;

use App\DTO\Api\ApiResponse;
use App\DTO\Api\ContentType\InfoPratique;
use App\Entity\MarqueVehicule;
use App\Services\AladhanApiService;
use App\Services\PharmacieApiService;
use App\Services\YahooWeatherApiService;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use App\Services\YahooWeather;

/**
 * @Rest\Route(path="/content_types", name="api_content_types_")
 */
class ContentController extends BaseController
{

    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="Home slider",
     *     @SWG\Parameter(
     *         name="lang",
     *         in="query",
     *         type="string",
     *         default="fr",
     *         description="Specify the user's language"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Slider successfully returned"
     *     )
     * )
     * @Rest\Get(
     *     path = "/slider",
     *     name = "slider"
     * )
     * @Rest\View(
     *     serializerGroups={"all", "slider"}
     * )
     *
     * @param ObjectManager $em
     * @return ApiResponse
     */
    public function slider(ObjectManager $em)
    {
        $slider = $em->getRepository('App:ItemList')->findOneByType('slider');
        return $this->respondWith($slider);
    }
    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="Home Products",
     *     @SWG\Parameter(
     *         name="lang",
     *         in="query",
     *         type="string",
     *         default="fr",
     *         description="Specify the user's language"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Products successfully returned"
     *     )
     * )
     * @Rest\Get(
     *     path = "/products",
     *     name = "products"
     * )
     * @Rest\View(
     *     serializerGroups={"all", "products"}
     * )
     *
     * @param ObjectManager $em
     * @return ApiResponse
     */
    public function products(ObjectManager $em)
    {
        $products = $em->getRepository('App:ItemList')->findOneByType('products');
        return $this->respondWith($products);
    }
    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="repair mode",
     *     @SWG\Parameter(
     *         name="lang",
     *         in="query",
     *         type="string",
     *         default="fr",
     *         description="Specify the user's language"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="repair modes successfully returned"
     *     )
     * )
     * @Rest\Get(
     *     path = "/modes",
     *     name = "modes"
     * )
     * @Rest\View(
     *     serializerGroups={"all", "modes"}
     * )
     *
     * @param ObjectManager $em
     * @return ApiResponse
     */
    public function reparation(ObjectManager $em)
    {
        $modes = $em->getRepository('App:ItemList')->findOneByType('modes_reparation');
        return $this->respondWith($modes);
    }
    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="cities",
     *     @SWG\Parameter(
     *         name="lang",
     *         in="query",
     *         type="string",
     *         default="fr",
     *         description="Specify the user's language"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="cities successfully returned"
     *     )
     * )
     * @Rest\Get(
     *     path = "/cities",
     *     name = "cities"
     * )
     * @Rest\View()
     *
     * @param ObjectManager $em
     * @return ApiResponse
     */
    public function cities(ObjectManager $em)
    {
        $villes = $em->getRepository('App:Ville')->findAll();
        return $this->respondWith($villes);
    }
    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="accidents",
     *     @SWG\Parameter(
     *         name="lang",
     *         in="query",
     *         type="string",
     *         default="fr",
     *         description="Specify the user's language"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="accident successfully returned"
     *     )
     * )
     * @Rest\Get(
     *     path = "/accidents",
     *     name = "accidents"
     * )
     * @Rest\View()
     *
     * @param ObjectManager $em
     * @return ApiResponse
     */
    public function accidents(ObjectManager $em)
    {
        $accident = $em->getRepository('App:Accident')->findAll();
        return $this->respondWith($accident);
    }
    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="marques",
     *     @SWG\Parameter(
     *         name="lang",
     *         in="query",
     *         type="string",
     *         default="fr",
     *         description="Specify the user's language"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="car models successfully returned"
     *     )
     * )
     * @Rest\Get(
     *     path = "/vehicule/marques",
     *     name = "vehicules"
     * )
     * @Rest\View()
     *
     * @param ObjectManager $em
     * @return ApiResponse
     */
    public function marquesVehicule(ObjectManager $em)
    {
        $marques = $em->getRepository('App:MarqueVehicule')->findAll();
        return $this->respondWith($marques);
    }
    /**
     * @SWG\Get(
     *     path = "/vehicule/modeles/{id}",
     *     tags={"Content Types"},
     *     description="modeles",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         type="string",
     *         description="Specify the car's marque"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="car's models successfully returned"
     *     )
     * )
     *
     * @Rest\Get(
     *     path = "/vehicule/modeles/{id}",
     *     name = "modeles"
     * )
     * @Rest\View
     *
     * @param MarqueVehicule $marque
     * @return ApiResponse
     */
    public function modelesVehicule(MarqueVehicule $marque)
    {
        $models = $marque->getModeleVehicules();
        return $this->respondWith($models);
    }
    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="Types sinistre",
     *     @SWG\Parameter(
     *         name="lang",
     *         in="query",
     *         type="string",
     *         default="fr",
     *         description="Specify the user's language"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="sinistre types successfully returned"
     *     )
     * )
     * @Rest\Get(
     *     path = "/sinitre/types",
     *     name = "type_sinistre"
     * )
     * @Rest\View(
     *     serializerGroups={"all", "sinistre"}
     * )
     *
     * @param ObjectManager $em
     * @return ApiResponse
     */
    public function typesSinistre(ObjectManager $em)
    {
        $products = $em->getRepository('App:ItemList')->findOneByType('sinistre');
        /**
         * @var \JMS\Serializer\Serializer $serializer
         */
        $serializer = $this->get('jms_serializer');
        $contextSerializer = $this->get('jms_serializer.serialization_context_factory')
            ->createSerializationContext()
            ->setGroups(['all', 'sinistre'])
            ->setSerializeNull(true);
        $products = $serializer->toArray($products, $contextSerializer);
        foreach ($products['items'] as &$value) {
            $value['active_icon'] = $value['image'];
            unset($value['image']);
        }
        return $this->respondWith($products);
    }
    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="Alertes",
     *     @SWG\Parameter(
     *         name="lang",
     *         in="query",
     *         type="string",
     *         default="fr",
     *         description="Specify the user's language"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="alerts types successfully returned"
     *     )
     * )
     *
     * @Rest\Get(
     *     path = "/alerts",
     *     name = "alerts"
     * )
     * @Rest\View
     *
     * @param ObjectManager $em
     * @return ApiResponse
     */
    public function Alerts(ObjectManager $em)
    {
        $alerts = $em->getRepository('App:Alert')->getCurrentAlerts();
        return $this->respondWith($alerts);
    }
    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="Info Pratiques",
     *     @SWG\Parameter(
     *         name="latitude",
     *         in="query",
     *         type="number",
     *         default="35",
     *         description="User geo-location latitude"
     *     ),
     *     @SWG\Parameter(
     *         name="longitude",
     *         in="query",
     *         type="number",
     *         default="-7",
     *         description="User geo-location longitude"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return practical information such as Adhan time, Weather and 'Pharmacie de garde' ..."
     *     )
     * )
     * @Rest\Get(
     *     path = "/infos_pratiques",
     *     name = "aladhan"
     * )
     * @Rest\View()
     *
     * @param AladhanApiService $aladhanApi
     * @param YahooWeatherApiService $weatherApi
     * @param PharmacieApiService $apiPharmacyApi
     * @param Request $request
     * @return ApiResponse
     */
    public function infosPratiques(AladhanApiService $aladhanApi, YahooWeatherApiService $weatherApi, PharmacieApiService $apiPharmacyApi, Request $request)
    {
//        $key = openssl_random_pseudo_bytes($length = 16, $crypto_strong);
//        dump($key, bin2hex($key), hex2bin(bin2hex($key)));
//        die;
//        $data = 'Omar El Maguiri';
//        $enc = $apiPharmacyApi->encrypt(json_encode($_SERVER));
//        $dec = $apiPharmacyApi->decrypt($enc);
//        dump($enc, $dec);
//        die;
        $pharmacies = $apiPharmacyApi->getPharmacy(33.5739983,-7.6584367);
        $prayer = $aladhanApi->getPrayer(33.5739983,-7.6584367);
        $weather = $weatherApi->getWeather(33.5739983,-7.6584367);
        dump($prayer, $weather, $pharmacies);
        die;
        return $this->respondWith(new InfoPratique($prayer, $weather));
    }

}