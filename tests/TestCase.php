<?php

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Actually runs a protected method of the given object.
     *
     * @param       $obj
     * @param       $method
     * @param array $args
     *
     * @return mixed
     */
    protected function callProtected($obj, $method, $args = [])
    {
        $methodObj = new ReflectionMethod(get_class($obj), $method);
        $methodObj->setAccessible(true);
        if (is_object($args)) {
            $args = [$args];
        } else {
            $args = (array) $args;
        }

        return $methodObj->invokeArgs($obj, $args);
    }

    /**
     * Assert that response is JSON and matches $expected.
     *
     * @param array|object $expected
     */
    public function assertJsonResponse($expected)
    {
        $this->assertEquals($expected, $this->getJsonFromResponse());
    }

    /**
     * Retrieve decoded JSON from response.
     *
     * @return array
     */
    protected function getJsonFromResponse()
    {
        return json_decode($this->response->getContent(), true);
    }
}
