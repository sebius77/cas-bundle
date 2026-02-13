<?php
/*
      Copyright 2026 CAS-BUNDLE - Sébastien Gaudin (sebastien.gaudin10@gmail.com)
      
      Licensed under the Apache License, Version 2.0 (the "License");
      you may not use this file except in compliance with the License.
      You may obtain a copy of the License at
      
          http://www.apache.org/licenses/LICENSE-2.0
      
      Unless required by applicable law or agreed to in writing, software
      distributed under the License is distributed on an "AS IS" BASIS,
      WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
      See the License for the specific language governing permissions and
      limitations under the License.
*/

namespace Sebius77\CasBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\EventDispatcher\EventDispatcherInterface;
use Sebius77\CasBundle\Event\CasAuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CasAuthenticator extends AbstractAuthenticator
{
    protected $server_login_url;
    protected $server_validation_url;
    protected $xml_namespace;
    protected $username_attribute;
    protected $query_ticket_parameter;
    protected $query_service_parameter;
    protected $options;
    protected $groups;

    private $eventDispatcher;
    private $client;

    public function __construct($config, HttpClientInterface $client, EventDispatcherInterface $eventDispatcher)
    {
        $this->server_login_url = $config['server_login_url'];
        $this->server_validation_url = $config['server_validation_url'];
        $this->xml_namespace = $config['xml_namespace'];
        $this->username_attribute = $config['username_attribute'];
        $this->query_service_parameter = $config['query_service_parameter'];
        $this->query_ticket_parameter = $config['query_ticket_parameter'];
        $this->options = $config['options'];

        $this->eventDispatcher = $eventDispatcher;
        $this->client = $client;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning 'false' will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return $request->query->has($this->query_ticket_parameter);
    }

    public function authenticate(Request $request): Passport
    {
        $url = $this->server_validation_url . '?' . $this->query_ticket_parameter . '=' .
            $request->query->get($this->query_ticket_parameter) . '&' .
            $this->query_service_parameter . '=' . urlencode($this->removeCasTicket($request->getUri()));

        $response = $this->client->request('GET', $url, $this->options);
        $xml = new \SimpleXMLElement($response->getContent(), 0, false, $this->xml_namespace, true);

        if (isset($xml->authenticationSuccess)) {
            $username = (array)$xml->authenticationSuccess[0];
            return new SelfValidatingPassport(new UserBadge($username['user']));
        } else
        throw new CustomUserMessageAuthenticationException('Authentication failed!');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?RedirectResponse
    {
        if ($request->query->has($this->query_ticket_parameter)) {
            return new RedirectResponse($this->removeCasTicket($request->getUri()));
        } else
        return null;
    }

    public function onAuthenticationFailure(Request $request, ?AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        $def_response = new JsonResponse($data, 403);

        $event = new CasAuthenticationFailureEvent($request, $exception, $def_response);
        $this->eventDispatcher->dispatch($event, CasAuthenticationFailureEvent::POST_MESSAGE);

        return $event->getResponse();
    }

    /**
     * Strip the CAS 'ticket' parameter from a uri.
     */
    protected function removeCasTicket($uri) {
        $parsed_url = parse_url($uri);
        // If there are no query parameters, then there is nothing to do.
        if (empty($parsed_url['query'])) {
            return $uri;
        }
        parse_str($parsed_url['query'], $query_params);
        // If there is no 'ticket' parameter, there is nothing to do.
        if (!isset($query_params[$this->query_ticket_parameter])) {
            return $uri;
        }
        // Remove the ticket parameter and rebuild the query string.
        unset($query_params[$this->query_ticket_parameter]);
        if (empty($query_params)) {
            unset($parsed_url['query']);
        } else {
            $parsed_url['query'] = http_build_query($query_params);
        }
  
        // Rebuild the URI from the parsed components.
        // Source: https://secure.php.net/manual/en/function.parse-url.php#106731
        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
        return "$scheme$user$pass$host$port$path$query$fragment";
      }
}
