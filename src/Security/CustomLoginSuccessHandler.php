<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CustomLoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
    {
        /** @var UserInterface $user */
        $user = $token->getUser();

        // Generate JWT token with custom payload
        $payload = [
            'uuid' => $user->getUuid(), // Add the UUID from the user entity
        ];
        $jwt = $this->jwtManager->createFromPayload($user, $payload);

        // Set the JWT token as an HttpOnly cookie
        $response = new JsonResponse(['message' => 'Login successful']);
        $response->headers->setCookie(
            new Cookie(
                'token',         // Cookie name
                $jwt,            // JWT token
                time() + 10800,   // Expiration time (1 hour here)
                '/',             // Path
                null,            // Domain (null = default)
                true,            // Secure (HTTPS only)
                true,            // HttpOnly (not accessible by JavaScript)
                false,           // Raw (not URL-encoded)
                'strict'         // SameSite policy
            )
        );

        return $response;
    }
}