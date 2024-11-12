<?php

namespace App\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $router;
    private $security;

    public function __construct(private UrlGeneratorInterface $urlGenerator,Security $security, RouterInterface $router)
    {
        $this->router = $router;
        $this->security = $security;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->getPayload()->getString('email');

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->getPayload()->getString('password')),
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }
    
    // public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    // {
    //     if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
    //         // return new RedirectResponse('home/homepage.html.twig');
    //         return new RedirectResponse($this->urlGenerator->generate($targetPath));
    //     }
    //     // For example:
    //     // return new RedirectResponse($this->urlGenerator->generate('some_route'));
    //      return new RedirectResponse($this->urlGenerator->generate('app_home_page'));
    //     // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    // }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response
    {
        // Get the user
        $user = $this->security->getUser();

        // Check if the user has the 'ROLE_ADMIN' role
        if ($this->security->isGranted('ROLE_ADMIN')) {
            // Redirect to the admin dashboard or any other route
            return new RedirectResponse($this->router->generate('app_admin'));
        }

        // If the user is not an admin, redirect to another route (e.g., homepage)
        return new RedirectResponse($this->router->generate('app_home'));
    }
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
