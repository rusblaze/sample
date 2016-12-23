<?php
// SessionTokenAuthenticator.php
/**
 * User: aivanov
 * Date: 22.12.16
 * Time: 11:50
 */
namespace Ai\CoreDomainBundle\Security;

use Ai\CoreDomain\User\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class SessionTokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var string
     */
    private $source;
    /**
     * @var string
     */
    private $sourceName;
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * SessionTokenAuthenticator constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param string $source
     * @param string $sourceName
     */
    public function __construct(UserRepositoryInterface $userRepository, string $source, string $sourceName)
    {
        $this->source = $source;
        $this->sourceName = $sourceName;
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new Response('Authentication Required', Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        switch ($this->source) {
            case 'header':
                $token = $request->headers->get($this->sourceName);
                break;
            case 'query':
                $token = $request->query->get($this->sourceName);
                break;
            default:
                return null;
        }
        if (is_null($token)) {
            return null;
        }

        // What you return here will be passed to getUser() as $credentials
        return array(
            'token' => $token,
        );
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiKey = $credentials['token'];

        // if null, authentication will fail
        // if a User object, checkCredentials() is called
        // It is not fully correct method, but...
        return $userProvider->loadUserByUsername($apiKey);
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        // check credentials - e.g. make sure the password is valid
        // no credential check is needed in this case

        // return true to cause authentication success
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = 'No active session found. Please relogin.';

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe()
    {
        return false;
    }

}