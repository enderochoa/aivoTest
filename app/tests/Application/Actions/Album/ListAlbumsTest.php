<?php

namespace Tests\Application\Actions\Album;

use DI\Container;
use Psr\Http\Message\StreamInterface;
use Tests\TestCase;

class ListAlbumsTest extends TestCase
{

    public function test_it_can_get_albums_from_an_artist_name()
    {
        $app = $this->getAppInstance();

        $request = $this->createRequest('GET', '/v1/albums?q=Vitico%20Castillo');
        $response = $app->handle($request);


        $body = json_decode((string)$response->getBody());

        $this->assertEquals($this->getExpectedResponse(), $body->data);
    }

    private function getExpectedResponse()
    {
        return json_decode(
            '
            [{
                "name": "Exitos de Vitico Castillo",
                "released": "2017-12-05",
                 "tracks": 20,
                 "cover": {
                     "height": 640,
                     "width": 640,
                     "url": "https://i.scdn.co/image/ab67616d0000b273b4772f702e8ec61c25dd2664"
                 }
         }]'
        );
    }

    private function getMockSpotifyResponse()
    {
        return '
        {
          "albums": {
            "href": "https://api.spotify.com/v1/search?query=vitico+castillo&type=album&offset=0&limit=20",
            "items": [
              {
                "album_type": "album",
                "artists": [
                  {
                    "external_urls": {
                      "spotify": "https://open.spotify.com/artist/0KMBgCcUDMtzdbLmF6EqAU"
                    },
                    "href": "https://api.spotify.com/v1/artists/0KMBgCcUDMtzdbLmF6EqAU",
                    "id": "0KMBgCcUDMtzdbLmF6EqAU",
                    "name": "Vitico Castillo",
                    "type": "artist",
                    "uri": "spotify:artist:0KMBgCcUDMtzdbLmF6EqAU"
                  }
                ],
                "available_markets": [
                  "AD",
                  "AE",
                  "AL",
                  "AR",
                  "AT",
                  "AU",
                  "BA",
                  "BE",
                  "BG",
                  "BH",
                  "BO",
                  "BR",
                  "BY",
                  "CA",
                  "CH",
                  "CL",
                  "CO",
                  "CR",
                  "CY",
                  "CZ",
                  "DE",
                  "DK",
                  "DO",
                  "DZ",
                  "EC",
                  "EE",
                  "EG",
                  "ES",
                  "FI",
                  "FR",
                  "GB",
                  "GR",
                  "GT",
                  "HK",
                  "HN",
                  "HR",
                  "HU",
                  "ID",
                  "IE",
                  "IL",
                  "IN",
                  "IS",
                  "IT",
                  "JO",
                  "JP",
                  "KW",
                  "KZ",
                  "LB",
                  "LI",
                  "LT",
                  "LU",
                  "LV",
                  "MA",
                  "MC",
                  "MD",
                  "ME",
                  "MK",
                  "MT",
                  "MX",
                  "MY",
                  "NI",
                  "NL",
                  "NO",
                  "NZ",
                  "OM",
                  "PA",
                  "PE",
                  "PH",
                  "PL",
                  "PS",
                  "PT",
                  "PY",
                  "QA",
                  "RO",
                  "RS",
                  "RU",
                  "SA",
                  "SE",
                  "SG",
                  "SI",
                  "SK",
                  "SV",
                  "TH",
                  "TN",
                  "TR",
                  "TW",
                  "UA",
                  "US",
                  "UY",
                  "VN",
                  "XK",
                  "ZA"
                ],
                "external_urls": {
                  "spotify": "https://open.spotify.com/album/1lLUk7XPFxURi990pH4yJt"
                },
                "href": "https://api.spotify.com/v1/albums/1lLUk7XPFxURi990pH4yJt",
                "id": "1lLUk7XPFxURi990pH4yJt",
                "images": [
                  {
                    "height": 640,
                    "url": "https://i.scdn.co/image/ab67616d0000b273b4772f702e8ec61c25dd2664",
                    "width": 640
                  },
                  {
                    "height": 300,
                    "url": "https://i.scdn.co/image/ab67616d00001e02b4772f702e8ec61c25dd2664",
                    "width": 300
                  },
                  {
                    "height": 64,
                    "url": "https://i.scdn.co/image/ab67616d00004851b4772f702e8ec61c25dd2664",
                    "width": 64
                  }
                ],
                "name": "Exitos de Vitico Castillo",
                "release_date": "2017-12-05",
                "release_date_precision": "day",
                "total_tracks": 20,
                "type": "album",
                "uri": "spotify:album:1lLUk7XPFxURi990pH4yJt"
              }
            ],
            "limit": 20,
            "next": null,
            "offset": 0,
            "previous": null,
            "total": 4
          }
        }';
    }
}