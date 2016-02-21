<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Assert\Assertion;
use Behat\Gherkin\Node\PyStringNode;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpContext implements Context, SnippetAcceptingContext
{
    /** @var \GuzzleHttp\Client */
    private $httpClient;
    /** @var RequestInterface */
    private $request;
    /** @var ResponseInterface */
    private $response;
    /** @var string */
    private $token;

    /**
     * @param string $baseUrl
     */
    public function __construct($baseUrl)
    {
        $this->httpClient = new \GuzzleHttp\Client([
            'http_errors' => false,
            'verify' => false,
            'base_uri' => $baseUrl,
        ]);
    }

    /**
     * @Given I generate token with username :username password :password
     */
    public function iGenerateTokenWithUsernamePassword($username, $password)
    {
        $form_params = [
            'username' => $username,
            'password' => $password,
        ];
        $loginRequest = new Request(
            'POST',
            'api/login', [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            http_build_query($form_params, null, '&')
        );

        $loginResponse = $this->httpClient->send($loginRequest);
        $data = json_decode($loginResponse->getBody()->getContents(), true);
        $this->token = (isset($data['token']) ? $data['token'] : '');
    }

    /**
     * @When I prepare a :arg1 request with token to :arg2
     */
    public function iPrepareARequestWithTokenTo($method, $uri)
    {
        $headers = [
            'Authorization' => sprintf('Bearer %s', $this->token),
        ];
        $this->request = new Request($method, $uri, $headers);
    }

    /**
     * @When I prepare a :arg1 request to :arg2
     */
    public function iPrepareARequestTo($method, $uri)
    {
        $this->request = new Request($method, $uri);
    }

    /**
     * @When I send the request
     */
    public function iSendTheRequest()
    {
        $this->response = $this->httpClient->send($this->request);
    }

    /**
     * @Then The response should have a JSON body like:
     */
    public function theResponseShouldHaveAJsonBodyLike(PyStringNode $body)
    {
        Assertion::eq(
            $this->cleanupJsonString($this->response->getBody()->getContents()),
            $this->cleanupJsonString($body->getRaw())
        );
    }
    /**
     * @Then the response status code should be :status
     */
    public function theResponseStatusCodeShouldBe($status)
    {
        Assertion::eq($this->response->getStatusCode(), $status);
    }
    /**
     * @Then the response status code should be :status with body:
     */
    public function theResponseStatusCodeShouldBeWithBody($status, PyStringNode $body)
    {
        $this->theResponseStatusCodeShouldBe($status);
        $this->theResponseShouldHaveAJsonBodyLike($body);
    }
    /**
     * @param string $jsonString
     * @return string
     */
    private function cleanupJsonString($jsonString)
    {
        return json_encode(json_decode($jsonString, true));
    }
}