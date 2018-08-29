<?php

namespace App\Controller\Api;

use App\DTO\Api\ApiResponse;
use App\DTO\Api\ContentType\InfoPratique;
use App\Entity\Alert;
use App\Entity\InsuranceType;
use App\Entity\MarqueVehicule;
use App\Entity\Societaire;
use App\Services\AladhanApiService;
use App\Services\PharmacieApiService;
use App\Services\YahooWeatherApiService;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route(path="/public/content_types", name="api_public_content_types_")
 */
class ContentController extends BaseController
{

    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="Home slider",
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
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
     * @Rest\Get(path = "/slider", name = "slider")
     * @Rest\View(serializerGroups={"all", "slider"})
     *
     * @ParamConverter("insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @param ObjectManager $em
     * @param InsuranceType $insuranceType
     * @return ApiResponse
     */
    public function slider(ObjectManager $em, InsuranceType $insuranceType)
    {
        $slider = $em->getRepository('App:ItemList')->findOneBy(['type' => 'slider', 'insuranceType' => $insuranceType]);
        return $this->respondWith($slider);
    }


    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="Home Circonstances Sinistre",
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
     *     @SWG\Parameter(
     *         name="lang",
     *         in="query",
     *         type="string",
     *         default="fr",
     *         description="Specify the user's language"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Circonstance Sinistre successfully returned"
     *     )
     * )
     *
     * @Rest\Get(path = "/circonstance-sinistre", name = "circonstance-sinistre")
     * @Rest\View(serializerGroups={"all", "circonstance-sinistre"})
     *
     * @ParamConverter("insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @param ObjectManager $em
     * @param InsuranceType $insuranceType
     * @return ApiResponse
     */
    public function circonstanceSinistre(ObjectManager $em, InsuranceType $insuranceType)
    {
        $circonstanceSinistres = $em->getRepository('App:CirconstanceSinistre')->findBy([ 'insuranceType' => $insuranceType]);
        return $this->respondWith($circonstanceSinistres);
    }
    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="Home Products",
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
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
     * @Rest\Get(path = "/products", name = "products")
     * @Rest\View(serializerGroups={"all", "products"})
     *
     * @ParamConverter("insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @param ObjectManager $em
     * @param InsuranceType $insuranceType
     * @return ApiResponse
     */
    public function products(ObjectManager $em, InsuranceType $insuranceType)
    {
        $products = $em->getRepository('App:ItemList')->findOneBy(['type' => 'products', 'insuranceType' => $insuranceType]);
        return $this->respondWith($products);
    }
    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="repair mode",
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
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
     * @Rest\Get(path = "/modes", name = "modes")
     * @Rest\View(serializerGroups={"all", "modes"})
     *
     * @ParamConverter("insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @param ObjectManager $em
     * @param InsuranceType $insuranceType
     * @return ApiResponse
     */
    public function reparation(ObjectManager $em, InsuranceType $insuranceType)
    {
        $modes = $em->getRepository('App:ItemList')->findOneBy(['type' => 'modes_reparation', 'insuranceType' => $insuranceType]);
        return $this->respondWith($modes);
    }
    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="cities",
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
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
     * @Rest\Get(path = "/cities", name = "cities")
     * @Rest\View()
     *
     * @ParamConverter("insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @param InsuranceType $insuranceType
     * @return ApiResponse
     */
    public function cities(InsuranceType $insuranceType)
    {
        return $this->respondWith($insuranceType->getVilles());
    }
    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="accidents",
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
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
     * @Rest\Get(path = "/accidents", name = "accidents")
     * @Rest\View()
     *
     * @ParamConverter("insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @param InsuranceType $insuranceType
     * @return ApiResponse
     */
    public function accidents(InsuranceType $insuranceType)
    {
        return $this->respondWith($insuranceType->getAccidents());
    }
    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="marques",
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
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
     * @Rest\Get(path = "/vehicule/marques", name = "vehicles")
     * @Rest\View()
     *
     * @ParamConverter("insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @param InsuranceType $insuranceType
     * @return ApiResponse
     */
    public function marquesVehicule(InsuranceType $insuranceType)
    {
        return $this->respondWith($insuranceType->getMarqueVehicules());
    }
    /**
     * @SWG\Get(
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
     * @Rest\Get(path = "/vehicule/modeles/{id}", name = "modeles")
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
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
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
     * @Rest\Get(path = "/sinitre/types", name = "type_sinistre")
     * @Rest\View(serializerGroups={"all", "sinistre"})
     *
     * @ParamConverter("insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @param ObjectManager $em
     * @param InsuranceType $insuranceType
     * @return ApiResponse
     */
    public function typesSinistre(ObjectManager $em, InsuranceType $insuranceType)
    {
        $sinistre = $em->getRepository('App:ItemList')->findOneBy(['type' => 'sinistre', 'insuranceType' => $insuranceType]);
        if (!$sinistre) {
            return $this->respondWith([]);
        }
        $serializer = $this->get('jms_serializer');
        $contextSerializer = $this->get('jms_serializer.serialization_context_factory')
            ->createSerializationContext()
            ->setGroups(['all', 'sinistre'])
            ->setSerializeNull(true);
        $products = $serializer->toArray($sinistre, $contextSerializer);
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
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
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
     * @Rest\Get(path = "/alerts", name = "alerts")
     * @Rest\View
     *
     * @ParamConverter("insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @param ObjectManager $em
     * @param InsuranceType $insuranceType
     * @return ApiResponse
     */
    public function Alerts(ObjectManager $em, InsuranceType $insuranceType)
    {
        $alerts = $em->getRepository('App:Alert')->getCurrentAlerts($insuranceType);
        return $this->respondWith($alerts);
    }


    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="Alertes",
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
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
     * @Rest\Get(path = "/alert/{id}", name = "alerts")
     * @Rest\View
     *
     * @ParamConverter("insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @param ObjectManager $em
     * @param InsuranceType $insuranceType
     * @param Alert $id
     * @return ApiResponse
     */
    public function UpdateAlerts(ObjectManager $em, InsuranceType $insuranceType,Alert $id)
    {
        $thisAlert = $em->getRepository('App:Alert')->find($id);
        $thisAlert->setChecked(true);
        $alerts = $em->getRepository('App:Alert')->getCurrentAlerts($insuranceType);
        return $this->respondWith($alerts);
    }
    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="Infos Pratiques",
     *     @SWG\Parameter(name="latitude", type="number", in="query"),
     *     @SWG\Parameter(name="longitude", type="number", in="query"),
     *     @SWG\Parameter(name="lang", in="query", type="string", default="fr", description="Specify the user's language"),
     *     @SWG\Response(
     *         response=200,
     *         description="Return practical information such as Adhan time, Weather and 'Pharmacie de garde' ..."
     *     )
     * )
     * @Rest\Get(path = "/infos_pratiques", name = "infos_pratiques")
     * @Rest\QueryParam(name="latitude", default="33.5739983", requirements="\-?\d+(\.\d+)?", description="User geo-location latitude")
     * @Rest\QueryParam(name="longitude", default="-7.6584367", requirements="\-?\d+(\.\d+)?", description="User geo-location longitude")
     * @Rest\View()
     *
     * @param double $latitude
     * @param double $longitude
     * @param AladhanApiService $aladhanApi
     * @param YahooWeatherApiService $weatherApi
     * @param PharmacieApiService $apiPharmacyApi
     * @param Request $request
     * @return ApiResponse
     */
    public function infosPratiques($latitude, $longitude, AladhanApiService $aladhanApi, YahooWeatherApiService $weatherApi, PharmacieApiService $apiPharmacyApi, Request $request)
    {
        $pharmacy = $apiPharmacyApi->getPharmacy($latitude,$longitude);
        $prayer = $aladhanApi->getPrayer($latitude,$longitude);
        $weather = $weatherApi->getWeather($latitude,$longitude);
        return $this->respondWith(new InfoPratique($prayer, $weather, $pharmacy));
    }
    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="Emergency Numbers",
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
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
     *     path = "/emergency",
     *     name = "emergency"
     * )
     * @Rest\View(
     *     serializerGroups={"all", "emergency"}
     * )
     *
     * @ParamConverter("insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @param ObjectManager $em
     * @param InsuranceType $insuranceType
     * @return ApiResponse
     */
    public function emergency(ObjectManager $em, InsuranceType $insuranceType)
    {
        $emergency = $em->getRepository('App:ItemList')->findOneBy(['type' => 'emergency', 'insuranceType' => $insuranceType]);
        return $this->respondWith($emergency);
    }

    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="Garages",
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="garages successfully returned"
     *     )
     * )
     * @Rest\Get(
     *     path = "/garages",
     *     name = "garages"
     * )
     * @Rest\View(
     *     serializerGroups={"all","garage"}
     * )
     *
     * @ParamConverter("insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @param  ObjectManager $em
     * @param  InsuranceType $insuranceType
     * @return ApiResponse
     */
    public function garage(ObjectManager $em, InsuranceType $insuranceType)
    {
        $garages = $em->getRepository('App:Garage')->findBy(['insuranceType' => $insuranceType]);
        return $this->respondWith($garages);
    }

    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="Experts",
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="experts data successfully returned"
     *     )
     * )
     * @Rest\Get(
     *     path = "/experts",
     *     name = "experts"
     * )
     * @Rest\View(
     *     serializerGroups={"all","expert"}
     * )
     *
     * @ParamConverter("insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @param  ObjectManager $em
     * @param  InsuranceType $insuranceType
     * @return ApiResponse
     */
    public function expert(ObjectManager $em, InsuranceType $insuranceType)
    {
        $experts = $em->getRepository('App:Expert')->findBy(['insuranceType' => $insuranceType]);
        return $this->respondWith($experts);
    }

    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="Agences",
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="garages successfully returned"
     *     )
     * )
     * @Rest\Get(
     *     path = "/agences",
     *     name = "agences"
     * )
     * @Rest\View(
     *     serializerGroups={"all","agence"}
     * )
     *
     * @ParamConverter("insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @param  ObjectManager $em
     * @param  InsuranceType $insuranceType
     * @return ApiResponse
     */
    public function agence(ObjectManager $em, InsuranceType $insuranceType)
    {

        $agences = $em->getRepository('App:Agence')->findBy(['insuranceType' => $insuranceType]);
        return $this->respondWith($agences);
    }

    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="societaire",
     *     @SWG\Parameter(
     *         name="Type",
     *         in="header",
     *         type="string",
     *         required=true,
     *         default="AUTO",
     *         description="Specify the type",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="societaire data successfully returned"
     *     )
     * )
     * @Rest\Get(
     *     path = "/societaire",
     *     name = "societaire"
     * )
     * @Rest\View(
     *     serializerGroups={"all","societaire"}
     * )
     *
     * @param  ObjectManager $em
     * @param  Request $request
     * @return ApiResponse
     */
    public function societaire(ObjectManager $em, Request $request)
    {
        $type = $request->headers->get("Type");
        $societaires = $em->getRepository('App:Societaire')->findByType($type);

        return $this->respondWith($societaires);
    }

    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="packs",
     *     @SWG\Parameter(
     *         name="X-CODE",
     *         in="header",
     *         type="string",
     *         required=true,
     *         description="code societaire",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="societaire data successfully returned"
     *     )
     * )
     * @Rest\Get(
     *     path = "/packs",
     *     name = "packs"
     * )
     * @Rest\View(
     *     serializerGroups={"all","pack"}
     * )
     *
     * @ParamConverter("societaireType", options={"converter":"App\ParamConverter\SocietaireParamConverter"})
     *
     * @param  ObjectManager $em
     * @param  Societaire $societaireType
     * @return ApiResponse
     */
    public function pack(ObjectManager $em, Societaire $societaireType)
    {

        $societaires = $em->getRepository('App:Pack')->findBy(["societaire" => $societaireType]);
        return $this->respondWith($societaires);
    }

    /*public function pack(ObjectManager $em,Request $request)
    {

        $code = $request->headers->get("x-code");

        $societaires = $em->getRepository('App:Pack')->findBy(["societaire" => $societaireType]);

        $societaires = $em->getRepository('App:Pack')->findByCode($code);
        return $this->respondWith($societaires);
    } */



    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="mrh categories",
     *     @SWG\Response(
     *         response=200,
     *         description="categorie data successfully returned"
     *     )
     * )
     * @Rest\Get(
     *     path = "/mrh/categories",
     *     name = "mrh_categories"
     * )
     * @Rest\View(
     *     serializerGroups={"all"}
     * )
     *
     *
     * @param  ObjectManager $em
     * @return ApiResponse
     */
    public function categories(ObjectManager $em)
    {
        $mrh_categories = $em->getRepository('App:MrhCategorie')->findAll();
        return $this->respondWith($mrh_categories);
    }

    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="produit contract",
     *     @SWG\Response(
     *         response=200,
     *         description="produit data successfully returned"
     *     )
     * )
     * @Rest\Get(
     *     path = "/contrat/produit",
     *     name = "produit_contrat"
     * )
     * @Rest\View(
     *     serializerGroups={"all"}
     * )
     *
     *
     * @param  ObjectManager $em
     * @return ApiResponse
     */
    public function produitContrat(ObjectManager $em)
    {
        $produits = $em->getRepository('App:ProduitContrat')->findAll();
        return $this->respondWith($produits);

    }

    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="mrh proprietes",
     *     @SWG\Response(
     *         response=200,
     *         description="propriete data successfully returned"
     *     )
     * )
     * @Rest\Get(
     *     path = "/mrh/proprietes",
     *     name = "mrh_proprietes"
     * )
     * @Rest\View(
     *     serializerGroups={"all"}
     * )
     *
     *
     * @param  ObjectManager $em
     * @return ApiResponse
     */
    public function proprietes(ObjectManager $em)
    {
        $mrh_proprietes = $em->getRepository('App:MrhPropriete')->findAll();
        return $this->respondWith($mrh_proprietes);
    }

    /**
     * @SWG\Get(
     *     tags={"Content Types"},
     *     description="mrh batiments",
     *     @SWG\Response(
     *         response=200,
     *         description="batiments data successfully returned"
     *     )
     * )
     * @Rest\Get(
     *     path = "/mrh/batiments",
     *     name = "mrh_batiments"
     * )
     * @Rest\View(
     *     serializerGroups={"all"}
     * )
     *
     *
     * @param  ObjectManager $em
     * @return ApiResponse
     */
    public function batiments(ObjectManager $em)
    {
        $mrh_batiments = $em->getRepository('App:MrhBatiment')->findAll();
        return $this->respondWith($mrh_batiments);
    }


}