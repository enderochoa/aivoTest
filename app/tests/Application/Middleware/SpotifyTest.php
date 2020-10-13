<?php

namespace Tests\Application\Middleware;

use App\Application\Middleware\Spotify;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Slim\App;

class SpotifyTest extends TestCase
{

    public function test_it_can_get_one_albums_from_an_artist_name()
    {
        $client = $this->getClientMocked(
            [
                $this->getSuccessResponse('token.json'),
                $this->getSuccessResponse('one_album_spotify.json')
            ]
        );

        $spotify = new Spotify($client);
        $albums = $spotify->getAlbumsFromArtist('Vitico Castillo');

        $expectedResponse = json_decode($this->readResponseFile('one_album_spotify_expeted_response.json'), true);
        $this->assertEquals($expectedResponse, $albums);
    }

    public function test_it_can_get_all_albums_from_an_artist_name(){
        $client = $this->getClientMocked(
            [
                $this->getSuccessResponse('token.json'),
                $this->getSuccessResponse('more_than_fifty_albums_spotify_page1.json'),
                $this->getSuccessResponse('more_than_fifty_albums_spotify_page2.json')
            ]
        );

        $spotify = new Spotify($client);
        $albums = $spotify->getAlbumsFromArtist('Ricardo Arjona');

        $expectedResponse = json_decode($this->readResponseFile('more_than_fifty_albums_expected_response.json'), true);
        $this->assertEquals($expectedResponse, $albums);
    }

    private function getClientMocked(array $responses)
    {
        $mock = new MockHandler();
        foreach ($responses as $response) {
            $mock->append($response);
        }
        $handler = HandlerStack::create($mock);
        return new Client(['handler' => $handler]);
    }

    private function getSuccessResponse($jsonResponse)
    {
        return new Response(
            200,
            [],
            $this->readResponseFile($jsonResponse)
        );
    }

    /**
     * @param $jsonResponse
     * @return false|string
     */
    private function readResponseFile($jsonResponse)
    {
        return file_get_contents(__DIR__ .'/Responses/'. $jsonResponse);
    }
}
