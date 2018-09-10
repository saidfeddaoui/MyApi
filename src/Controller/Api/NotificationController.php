<?php

namespace App\Controller\Api;


use App\DTO\Api\ApiResponse;
use App\Entity\Notification;
use App\Entity\NotificationDetail;
use App\Services\Validator;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @Rest\Route(path="/public/get/notification", name="api_notification_")
 */
class NotificationController extends BaseController
{
    /**
     * @SWG\Post(
     *     tags={"Notification"},
     *     description="getNotification Process",
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
     *     @SWG\Parameter(
     *        name="Device",
     *        in="body",
     *        description="Notication object",
     *        required=true,
     *        @Model(type="App\Entity\Notification", groups={"all"})
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Failure response",
     *         @Model(type="App\DTO\Api\ApiResponse", groups={"all"}),
     *         examples={
     *             "Validation Error (Http Code: 406)":
     *             {
     *                 "code"=406,
     *                 "status"="Constraint Violation Error"
     *             },
     *             "Not Found Error (Http Code: 404)":
     *             {
     *                 "code"=404,
     *                 "status"="Resource Not Found"
     *             }
     *         }
     *     )
     * )
     *
     * @Rest\Post(path="/detailss",name="detailss")
     * @Rest\View(serializerGroups={"all"})
     *
     * @param ApiDevices $apiDevices
     * @param Request $request
     * @param Validator $validator
     * @return JsonResponse
     */
    public function getNotificationAction(Request $request, Validator $validator)
    {
        // check Method
        $method = $validator->checkMethod('POST');
        if ($method['status'] == 'OK'){
            // Params
            $id = $request->request->get('id');
            $params = array(
                'id'=> $id,
            );

            $status = $validator->checkParams($params);
            if($status['status'] ==  "OK"){
                $detailNotifications = array();
                $em = $this->getDoctrine()->getManager();
                $notification = $em->getRepository(Notification::class)->find($id);
                if ($notification instanceof Notification){
                  foreach ($notification->getNotificationDetails() as $notificationDetail){
                      if ($notificationDetail instanceof NotificationDetail)
                      array_push(
                          $detailNotifications,
                          array(
                              'id' => $notificationDetail->getId(),
                              'sujet' => $notificationDetail->getSujet(),
                              'message' => $notificationDetail->getMessage(),
                              'get_id_societaire' => $notificationDetail->getIdSocietaire(),
                          )
                      );
                  }
                }
                $response['header'] = array('status'=>'OK','message'=>'');;
                $response['results'] = $detailNotifications;
                return new JsonResponse($response);

            }else{
                $response['header'] = $status;
                $response['results'] = array();
                return new JsonResponse($response);
            }
        }else{
            $response['header'] = $method;
            $response['results'] = array();
            return new JsonResponse($response);
        }
    }




    //liste Notification

    /**
     * @SWG\Post(
     *     tags={"Notification"},
     *     description="detail Notifiocation",
     *     @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         type="string",
     *         required=true,
     *         description="Bearer auth",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return detail Notification",
     *     )
     * )
     *
     * @Rest\Get(path="/detail/{notification_id}", name="detail")
     * @Rest\View()
     * @param $notification_id
     * @param ObjectManager $em
     * @return ApiResponse
     */
    public function detailsNotification(ObjectManager $em,  $notification_id)
    {
        $notifications = $em->getRepository("App:Notification")->findOneById($notification_id);
        return $this->respondWith($notifications);
    }



    //liste Notification

    /**
     * @SWG\Get(
     *     tags={"Notification"},
     *     description="Notifiocation list",
     *     @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         type="string",
     *         required=true,
     *         description="Bearer auth",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return attachments list",
     *     )
     * )
     *
     * @Rest\Post(path="/list/{preDeclaration_id}", name="list")
     * @Rest\View()
     * @param $preDeclaration_id
     * @param ObjectManager $em
     * @return ApiResponse
     */
    public function listNotification(ObjectManager $em,  $preDeclaration_id)
    {
        $listNotification = $em->getRepository("App:Notification")->findByidPredeclaration($preDeclaration_id);
        return $this->respondWith($listNotification);
    }



}
