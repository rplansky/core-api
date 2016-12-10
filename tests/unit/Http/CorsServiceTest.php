<?php
namespace Boitata\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery as m;
use TestCase;

class CorsServiceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        config(['app.url' => 'http://www.bar.com.br']);
    }

    /**
     * @dataProvider getOrigins
     */
    public function testShouldAddCorsHeaderToValidOrigins($origin, $expected)
    {
        // Set
        $service = new CorsService;
        $request = new Request;
        $request->headers->set('Origin', $origin);
        $response = new Response;

        // Actions
        $response = $service->handle($request, $response);

        // Assertions
        $headers = $response->headers;
        if ($expected) {
            $this->assertTrue($headers->has('Access-Control-Allow-Origin'));
            $this->assertTrue($headers->has('Access-Control-Allow-Credentials'));
            $this->assertTrue($headers->has('Vary'));
            $this->assertEquals($origin, $headers->get('Access-Control-Allow-Origin'));
            $this->assertEquals('true', $headers->get('Access-Control-Allow-Credentials'));
            $this->assertEquals(
                'GET, HEAD, PATCH, PUT, POST, DELETE, OPTIONS',
                $headers->get('Access-Control-Allow-Methods')
            );
            $this->assertEquals('Content-Type, Authorization', $headers->get('Access-Control-Allow-Headers'));
            $this->assertEquals('Origin', $headers->get('Vary'));
        } else {
            $this->assertFalse($headers->has('Access-Control-Allow-Origin'));
            $this->assertFalse($headers->has('Access-Control-Allow-Credentials'));
            $this->assertFalse($headers->has('Access-Control-Allow-Methods'));
            $this->assertFalse($headers->has('Access-Control-Allow-Headers'));
            $this->assertFalse($headers->has('Vary'));
        }
    }

    public function testShouldSkipCorsHeaderIfOriginIsEqualToHost()
    {
        // Set
        $service  = new CorsService;
        $request  = new Request;
        $response = new JsonResponse;

        $request->headers->set('Origin', 'http://foo.bar.com.br');
        $request->headers->set('Host', 'foo.bar.com.br');

        // Actions
        $response = $service->handle($request, $response);

        // Assertions
        $headers = $response->headers;
        $this->assertFalse($headers->has('Access-Control-Allow-Origin'));
        $this->assertFalse($headers->has('Access-Control-Allow-Credentials'));
        $this->assertFalse($headers->has('Access-Control-Allow-Methods'));
        $this->assertFalse($headers->has('Access-Control-Allow-Headers'));
        $this->assertFalse($headers->has('Vary'));
    }

    public function testShouldAddCorsHeaderToMappedSearchDomainWhenOnStaging()
    {
        // Set
        $service  = new CorsService;
        $request  = new Request;
        $response = new Response;
        $origin   = 'https://john.foo.bar.com.br';
        $request->headers->set('Origin', $origin);
        config(['app.url' => 'foo.bar.com.br/']);

        // Actions
        $response = $service->handle($request, $response);

        // Assertions
        $headers = $response->headers;
        $this->assertTrue($headers->has('Access-Control-Allow-Origin'));
        $this->assertTrue($headers->has('Access-Control-Allow-Credentials'));
        $this->assertTrue($headers->has('Access-Control-Allow-Methods'));
        $this->assertTrue($headers->has('Access-Control-Allow-Headers'));
        $this->assertTrue($headers->has('Vary'));
        $this->assertEquals($origin, $headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('true', $headers->get('Access-Control-Allow-Credentials'));
        $this->assertEquals(
            'GET, HEAD, PATCH, PUT, POST, DELETE, OPTIONS',
            $headers->get('Access-Control-Allow-Methods')
        );
        $this->assertEquals('Origin', $headers->get('Vary'));
    }

    public function getOrigins()
    {
        return [
            ['http://www.bar.com.br', true],
            ['https://staging.bar.com.br', true],
            ['http://develop.bar.com.br', true],
            ['http://john.bar.com.br', true],
            ['https://john.foo.bar.com.br', false],
            ['http://john.foo.bar.com.br', false],
            'github pages should be allowed'          => ['https://boitata-publication-platform.github.io', true],
            'not a CORS request'                      => [null, false],
            'empty origin'                            => ['', false],
            'no CORS with localhost'                  => ['http://localhost:8000', false],
            'strange domain'                          => ['http://anotherdomain.com', false],
            'stranger domain'                         => [
                'http://www.foo.com.br.anotherdomain.com',
                false,
            ],
            'stranger github pages should be blocked' => [
                'https://boitata-publication-platform.github.io.anotherdomain.com',
                false,
            ],
        ];
    }
}