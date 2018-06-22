<?php

namespace App\Controller\Api;

use App\Entity\MarqueVehicule;
use App\Services\AladhanApiService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use App\Services\YahooWeather;
use App\Services\aladhan;

/**
 * @Rest\Route(path="/content_types", name="api_content_types_")
 */
class ContentController extends FOSRestController
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
     *
     * @Rest\Get(
     *     path = "/slider",
     *     name = "slider"
     * )
     * @Rest\View(
     *     serializerGroups={"all", "slider"}
     * )
     */
    public function slider()
    {

        $em = $this->getDoctrine()->getManager();
        $slider = $em->getRepository('App:ItemList')->findOneByType('slider');
        return array("response"=>$slider);
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
     *
     * @Rest\Get(
     *     path = "/products",
     *     name = "products"
     * )
     * @Rest\View(
     *     serializerGroups={"all", "products"}
     * )
     */
    public function products()
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('App:ItemList')->findOneByType('products');
        return array("response"=>$products);
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
     *
     * @Rest\Get(
     *     path = "/modes",
     *     name = "modes"
     * )
     * @Rest\View(
     *     serializerGroups={"all", "modes"}
     * )
     */
    public function reparation()
    {
        $em = $this->getDoctrine()->getManager();
        $modes = $em->getRepository('App:ItemList')->findOneByType('modes_reparation');
        return array("response"=>$modes);
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
     *
     * @Rest\Get(
     *     path = "/cities",
     *     name = "cities"
     * )
     * @Rest\View(
     * )
     */
    public function cities()
    {
        $em = $this->getDoctrine()->getManager();
        $villes = $em->getRepository('App:Ville')->findAll();
        return array("response"=>$villes);
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
     *
     * @Rest\Get(
     *     path = "/accidents",
     *     name = "accidents"
     * )
     * @Rest\View(
     * )
     */
    public function accidents()
    {
        $em = $this->getDoctrine()->getManager();
        $accident = $em->getRepository('App:Accident')->findAll();
        return array("response"=>$accident);
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
     *
     * @Rest\Get(
     *     path = "/vehicule/marques",
     *     name = "vehicules"
     * )
     * @Rest\View(
     * )
     */
    public function marquesVehicule()
    {
        $em = $this->getDoctrine()->getManager();
        $marques = $em->getRepository('App:MarqueVehicule')->findAll();
        return array("response"=>$marques);
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
     */
    public function modelesVehicule(MarqueVehicule $marque)
    {
        $models = $marque->getModeleVehicules();
        return array("response"=>$models);
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
     *
     * @Rest\Get(
     *     path = "/sinitre/types",
     *     name = "type_sinistre"
     * )
     * @Rest\View(
     *     serializerGroups={"all", "sinistre"}
     * )
     */
    public function typesSinistre()
    {
        $em = $this->getDoctrine()->getManager();
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
        return array("response"=>$products);
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
     */
    public function Alerts()
    {
        $em = $this->getDoctrine()->getManager();
        $alerts = $em->getRepository('App:Alert')->getCurrentAlerts();
        return array("response"=>$alerts);
    }


    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="aladhan",
     *     @SWG\Parameter(
     *         name="city",
     *         in="query",
     *         type="string",
     *         default="fr",
     *         description="Specify the user's city"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="adan timing successfully returned"
     *     )
     * )
     *
     * @Rest\Get(
     *     path = "/aladhan",
     *     name = "aladhan"
     * )
     * @Rest\View(
     * )
     *
     * @param AladhanApiService $apiAladhan
     * @param Request $request
     * @return array
     */
    public function aladhan(AladhanApiService $apiAladhan, Request $request)
    {
        $data = $apiAladhan->getTimingsData();
        return [ "response" => [ $data->getUpcomingPrayerTime() ] ];
    }
    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="weather",
     *     @SWG\Parameter(
     *         name="city",
     *         in="query",
     *         type="string",
     *         default="fr",
     *         description="Specify the user's city"
     *     ),
     *      @SWG\Parameter(
     *         name="lang",
     *         in="query",
     *         type="string",
     *         default="fr",
     *         description="Specify the user's language"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="weather successfully returned"
     *     )
     * )
     *
     * @Rest\Get(
     *     path = "/weather",
     *     name = "weather"
     * )
     * @Rest\View(
     * )
     */
    public function weather(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $request->get('city');
        $lang = $request->get('lang');
        $weather = array();

        $aCities = array(array("ville"=>"Casablanca","code"=>"1532755","ville_ar"=>"الدار البيضاء"),
            array("ville"=>"Rabat","code"=>"1539359","ville_ar"=>"الرباط"),
            array("ville"=>"Tanger","code"=>"1540935","ville_ar"=>"طنجة"),
            array("ville"=>"Oujda","code"=>"1538412","ville_ar"=>"وجدة"),
            array("ville"=>"Agadir","code"=>"1542773","ville_ar"=>"أكادير"),
            array("ville"=>"El jadida","code"=>"1534936","ville_ar"=>"الجديدة"),
            array("ville"=>"Khouribga","code"=>"1537353"),
            array("ville"=>"Beni-Mellal","code"=>"1532231","ville_ar"=>"بني ملال"),
            array("ville"=>"Marrakech","code"=>"1537782","ville_ar"=>"مراكش"),
            array("ville"=>"Meknes","code"=>"1537862","ville_ar"=>"مكناس"),
            array("ville"=>"Fes","code"=>"1535450","ville_ar"=>"فاس"),
            array("ville"=>"Taza","code"=>"1541306","ville_ar"=>"تازة"),
            array("ville"=>"Tetouan","code"=>"1541445","ville_ar"=>"تطوان"),
            array("ville"=>"Kenitra","code"=>"1537281"),
            array("ville"=>"Larache","code"=>"1537598","ville_ar"=>"العرائش")
        );
        foreach ($aCities as $key =>  $value){
            if(strtolower($value["ville"]) == strtolower($city)){
                $yh = new YahooWeather($value["code"]);
                $weather = array("ville"=>($lang == 'ar') ? $value["ville_ar"]:$value["ville"],
                                 "temperature"=>$yh->getTemperature(false),
                                 "vent"=>$yh->getWindSpeed(),
                                 "image"=>substr($yh->getYahooIcon(),10,37),
                                 "description"=>$yh->getDescription()

                    );
                return array("response"=>$weather);
            }
    }
        return array("response"=>$weather);
    }

}