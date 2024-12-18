<?php

namespace Sebius77\CasBundle\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    protected $server_login_url;
    protected $query_service_parameter;

    /**
     * @param $config
     */
    public function __construct($config)
    {
        $this->server_login_url = $config['server_login_url'];
        $this->query_service_parameter = $config['query_service_parameter'];
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return Response
     */
    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return new RedirectResponse(
            $this->server_login_url.'?' .
            $this->query_service_parameter .
            '=' . urlencode($request->getUri())
        );
    }
}
