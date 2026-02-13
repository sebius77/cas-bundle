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

namespace Sebius77\CasBundle\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class CasAuthenticationFailureEvent {

    const POST_MESSAGE = 'cas_auth.authentication.failure';

    private $request;
    private $exception;
    private $response;

    public function __construct(Request $request, AuthenticationException $exception, Response $response) {
        $this->request = $request;
        $this->exception = $exception;
        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * @return Request
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @return AuthenticationException
     */
    public function getException() {
        return $this->exception;
    }

    /**
     * @return string
     */
    public function getExceptionType() {
        return get_class($this->exception);
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response) {
        $this->response = $response;
    }
}
