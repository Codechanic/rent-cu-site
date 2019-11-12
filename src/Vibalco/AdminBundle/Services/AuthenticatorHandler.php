<?php


namespace Vibalco\AdminBundle\Services;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticatorHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    private $router;
    private $session;

    /**
     * AuthenticatorHandler constructor.
     * @param $router
     * @param $session
     */
    public function __construct(RouterInterface $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
    }


    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
        if ( $request->isXmlHttpRequest() ) {
            $array = array( 'success' => false, 'message' => $exception->getMessage() );
            $response  = new Response(json_encode($array));
            $response->headers->set( 'Content-Type', 'application/json' );
            return $response;
        } else {
            $request->getSession()->set(SecurityContextInterface::AUTHENTICATION_ERROR, $exception);
            return new RedirectResponse($this->router->generate( 'login') );
        }


    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param TokenInterface $token
     *
     * @return Response never null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        // TODO: Implement onAuthenticationSuccess() method.
        if ( $request->isXmlHttpRequest() ) {
            $array = array( 'success' => true );

            $response  = new Response(json_encode($array));
            $response->headers->set( 'Content-Type', 'application/json' );
            return $response;
        } else {
            if ( $this->session->get('_security.main.target_path') ) {
                $url = $this->session->get('_security.main.target_path');
            } else {
                $url = $this->router->generate('homepage');
            }

            return new RedirectResponse( $url );
        }
    }
}