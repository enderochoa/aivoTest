<?php

namespace App\Application\Middleware;

use GuzzleHttp;
use Psr\Http\Client\ClientInterface;

class Spotify
{
    /**
     * @var GuzzleHttp\Client
     */
    private $client;

    public function __construct(?ClientInterface $client = null)
    {
        $this->client = $client ? $client : new GuzzleHttp\Client();
    }

    /**
     * @param $artistName
     * @return array
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function getAlbumsFromArtist(string $artistName): array
    {
        $token = $this->login();
        $albums = [];
        $offset = 0;
        $limit = 50;
        do {
            $response = $this->client->request(
                'GET',
                'https://api.spotify.com/v1/search',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'Authorization' => "Bearer " . $token
                    ],
                    'query' => [
                        'q' => $artistName,
                        'type' => 'album',
                        'limit' => $limit,
                        'offset' => $offset
                    ]
                ]
            );

            $content = json_decode($response->getBody()->getContents());

            if ($thereAreMoreAlbums = (bool)$content->albums->next) {
                parse_str($content->albums->next,$params);
                $limit = $params['limit'];
                $offset = $params['offset'];
            }

            $albums = array_merge($albums, $content->albums->items);
        } while ($thereAreMoreAlbums);

        return $this->getResponse($albums);
    }

    private function getResponse(array $albums)
    {
        $response = array();

        foreach ($albums as $album) {
            $response[] = $this->getAlbumResponse($album);
        }

        return $response;
    }

    private function getAlbumResponse($album)
    {
        return [
            'name' => $album->name,
            'released' => $album->release_date,
            'tracks' => $album->total_tracks,
            'cover' => $this->getAlbumCover($album->images)
        ];
    }

    private function getAlbumCover(array $images)
    {
        $firstImage = $images[0];
        return [
            'height' => $firstImage->height,
            'width' => $firstImage->width,
            'url' => $firstImage->url
        ];
    }

    private function login()
    {
        $response = $this->client->request('POST',
            'https://accounts.spotify.com/api/token',
            [
               'headers' => [
                   'Authorization' => 'Basic NjE1ZmFiYzFhZGFlNGNiZWJiN2U2NGRiOWJjOTA2ZWE6NjRiZjQ5OGZkNzNhNDg0NzlmMjgwMTUxOTg2Y2MyMDE=',
                   'Content-Type'=>'application/x-www-form-urlencoded'
               ],
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ]
            ]
        );

        return json_decode($response->getBody()->getContents())->access_token;
    }
}