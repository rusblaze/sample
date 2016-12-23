<?php
// EmailPasswordAuthenticator.php
/**
 * User: aivanov
 * Date: 22.12.16
 * Time: 14:58
 */
namespace Ai\CoreDomainBundle\Security;

use Ai\CoreDomain\User\Exception\UserNotFoundException;
use Ai\CoreDomain\User\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class EmailPasswordAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * SessionTokenAuthenticator constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepositoryInterface $userRepository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse('Auth data required', JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        if (!is_null($email = $request->get('email'))) {
            return [
                'email' => $email,
                'password' => $request->get('password'),
            ];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $email = $credentials['email'];

        try {
            return $this->userRepository->findByEmail($email);
        } catch (UserNotFoundException $e) {
            throw new AuthenticationException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!$this->passwordEncoder->isPasswordValid($user, $credentials['password'])) {
            throw new AuthenticationException('Password is incorrect');
        } else {
            return true;
        }
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(
            ['message' => strtr($exception->getMessageKey(), $exception->getMessageData())],
            JsonResponse::HTTP_FORBIDDEN
        );
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $user = $token->getUser();
        if (is_null($sessionKey = $user->getActiveSession())) {
            $sessionKey = Uuid::uuid4();
            $user->startSession($sessionKey->toString());
            $this->userRepository->update($user);
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe()
    {
        // TODO: Implement supportsRememberMe() method.
    }
}