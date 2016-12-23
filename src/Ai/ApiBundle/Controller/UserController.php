<?php
// UserController.php
/**
 * User: aivanov
 * Date: 20.12.16
 * Time: 11:21
 */
namespace Ai\ApiBundle\Controller;

//use FOS\RestBundle\Controller\Annotations as Rest;
use Ai\CoreDomain\User\Role;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TimeInc\SwaggerBundle\Swagger\Annotation as TimeInc;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class UserController
 * @package Ai\ApiBundle\Controller
 */
class UserController extends SecuredControllerAbstract
{
    /**
     * @SWG\Get(path="/user",
     *   tags={"user"},
     *   summary="Get user list",
     *   description="",
     *   operationId="export",
     *   produces={"text/csv", "application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(
     *       type="array",
     *       @SWG\Items(ref="#/definitions/ExistingUser")
     *     ),
     *   ),
     *   @SWG\Response(response="default", description="an ""unexpected"" error"),
     *   security={
     *     {"api_key": {}}
     *   }
     * )
     *
     */
    public function exportAction()
    {
        $users = $this->get('user_repository')->findAll();
        $serializer = $this->get('serializer');

        $context = new SerializationContext();
        $context->setSerializeNull(true)
            ->setGroups(['export']);
        $data = $serializer->toArray($users, $context);

        $view = $this->view($data, 200)
                     ->setTemplateVar('users');

        return $this->handleView($view);
    }

    /**
     * @SWG\Post(
     *     path="/user",
     *     tags={"user"},
     *     operationId="update",
     *     summary="Update users list",
     *     description="",
     *     consumes={"text/csv", "application/json"},
     *     produces={"text/plain"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Users list that needs to be stored",
     *         required=true,
     *         collectionFormat="csv",
     *         @SWG\Schema(
     *           type="array",
     *           @SWG\Items(ref="#/definitions/UpdateUser")
     *         )
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid input",
     *     ),
     *     security={
     *       {"api_key": {}}
     *     }
     * )
     */
    public function updateAction(Request $request)
    {
        $serializer = $this->get('serializer');
        $validator = $this->get('validator');

        $inputData = $request->request->all();
        $users = [];
        foreach($request->request->all() as $userData) {
            if (!isset($userData['role'])) {
                $userData['role'] = null;
            } else if (!is_null($userData['role']) && $userData['role'] != '') {
                $userData['role'] = ['role' => $userData['role']];
            } else {
                unset($userData['role']);
            }
            $users[] = $serializer->fromArray(
                $userData,
                'Ai\CoreDomainBundle\Entity\User'
            );
        }

        $validationErrors = $validator->validate($users);

        foreach ($users as $user) {
            $validationErrors->addAll($validator->validate($user->getRole()));
        }
        if (count($validationErrors) > 0) {
            $errors = [];
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            foreach ($validationErrors as $validationError) {
                $errors[] = (string) $validationError;
            }

            $view = $this->view(['errors' => $errors], Response::HTTP_BAD_REQUEST)
                         ->setTemplateVar('errors')
                         ->setFormat('html')
                         ->setTemplate('AiApiBundle:User:update_error.html.twig');

            return $this->handleView($view);
        } else {
            $userService = $this->get('ai.api.user_service');
            $errors = $userService->updateUsersList($users);

            if (count($errors) > 0) {
                $view = $this->view(['errors' => $errors], Response::HTTP_OK)
                    ->setTemplateVar('errors')
                    ->setFormat('html')
                    ->setTemplate('AiApiBundle:User:update_error.html.twig');;
                return $this->handleView($view);
            } else {
                return new Response('', Response::HTTP_NO_CONTENT);
            }
        }

//        $users = $this->get('user_repository')->findAll();
//
//        return array('users' => $users);
    }
}