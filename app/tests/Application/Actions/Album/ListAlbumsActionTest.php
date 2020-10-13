<?php

namespace Tests\Application\Actions\Album;

use DI\Container;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\StreamInterface;
use Slim\App;
use Tests\TestCase;

class ListAlbumsActionTest extends TestCase
{


    public function test_it_can_get_albums_from_an_artist_name()
    {
        $app = $this->getAppInstance();

        $request = $this->createRequest(
            'GET',
            '/v1/albums',
            [
                'query_params'=>'q=Vitico Castillo'
            ]
        );

        $response = $app->handle($request);
        $body = json_decode((string)$response->getBody());


//        $this->assertEquals($this->getExpectedResponse(), $body->data);
    }

}