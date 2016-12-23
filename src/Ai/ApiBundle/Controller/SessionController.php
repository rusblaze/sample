<?php
// SessionController.php
/**
 * User: aivanov
 * Date: 22.12.16
 * Time: 14:18
 */
namespace Ai\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionController extends SecuredControllerAbstract
{
    /**
     * @SWG\Get(path="/session",
     *   tags={"session"},
     *   summary="Get current user session",
     *   description="",
     *   operationId="get_session",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/Session")
     *   ),
     *   @SWG\Response(
     *     response=401,
     *     description="No session found. Need login"
     *   ),
     *   @SWG\Response(
     *     response=403,
     *     description="Wrong credentials supplied"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   ),
     *   security={
     *     {"api_key": {}}
     *   }
     * )
     *
     */
    public function sessionAction()
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $serializer = $this->get('jms_serializer');
        $context = new SerializationContext();
        $context->setSerializeNull(true)
            ->setGroups(['session']);
        $currentUserDto = $serializer->serialize($currentUser, 'json', $context);
        return new JsonResponse($currentUserDto, JsonResponse::HTTP_OK, [], true);
    }

    /**
     * @SWG\Put(path="/session",
     *   tags={"session"},
     *   summary="Create new session for user",
     *   description="",
     *   operationId="login",
     *   consumes={"application/x-www-form-urlencoded"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="User email",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="User password",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/Session")
     *   ),
     *   @SWG\Response(
     *     response=403,
     *     description="Wrong credentials supplied"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     *
     */
    public function loginAction(Request $request)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $serializer = $this->get('jms_serializer');
        $context = new SerializationContext();
        $context->setSerializeNull(true)
                ->setGroups(['session']);
        $currentUserDto = $serializer->serialize($currentUser, 'json', $context);
        return new JsonResponse($currentUserDto, JsonResponse::HTTP_CREATED, [], true);
    }

    /**
     * @SWG\Delete(path="/session",
     *   tags={"session"},
     *   summary="Delete current user session (logout)",
     *   description="",
     *   operationId="logout",
     *   @SWG\Response(
     *     response=201,
     *     description="successful operation"
     *   ),
     *   @SWG\Response(
     *     response=403,
     *     description="Wrong credentials supplied"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   ),
     *   security={
     *     {"api_key": {}}
     *   }
     * )
     *
     */
    public function logoutAction()
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $currentUser->endSession();
        $this->get('user_repository')->update($currentUser);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}