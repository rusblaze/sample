<?php
// ControllerAbstract.php
/**
 * User: aivanov
 * Date: 22.12.16
 * Time: 18:01
 */
namespace Ai\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Swagger\Annotations as SWG;

abstract class SecuredControllerAbstract extends FOSRestController
{
    /**
     * @SWG\SecurityScheme(
     *   securityDefinition="api_key",
     *   type="apiKey",
     *   in="header",
     *   name="X-AUTH-SESSION"
     * )
     */

}