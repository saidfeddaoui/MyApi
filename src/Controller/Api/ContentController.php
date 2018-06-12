<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\EventSubscriber\PathSerializerEventSubscriber;

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
}
