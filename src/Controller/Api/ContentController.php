<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\EventSubscriber\PathSerializerEventSubscriber;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Services\YahooWeather;
use App\Services\aladhan;

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
     *     path = "/api/slider",
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
     *     description="Home Producuts",
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
     *     path = "/api/products",
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
     *     path = "/api/modes",
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
     *     path = "/api/cities",
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
     *     path = "/api/vehicule/marques",
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
     *     tags={"Content Types"},
     *     description="modeles",
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
     *     path = "/api/vehicule/modeles",
     *     name = "modeles"
     * )
     * @Rest\View(
     * )
     */
    public function modelesVehicule()
    {
        $em = $this->getDoctrine()->getManager();
        $models = $em->getRepository('App:ModeleVehicule')->findAll();
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
     *     path = "/api/sinitre/types",
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
        return array("response"=>$products);
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
     *         description="Specify the user's language"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="adan timing successfully returned"
     *     )
     * )
     *
     * @Rest\Get(
     *     path = "/api/aladhan",
     *     name = "aladhan"
     * )
     * @Rest\View(
     * )
     */
    public function aladhan(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $request->get('city');
        $aladhan = new aladhan($city);
        $timings = $aladhan->getTimings();
        return array("response"=>$timings);
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
     *     path = "/api/weather",
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
