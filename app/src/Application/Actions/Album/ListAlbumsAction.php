<?php


namespace App\Application\Actions\Album;


use App\Application\Actions\Action;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class ListAlbumsAction extends Action
{

    protected function action(): Response
    {
        $response = json_decode('
            [{
                "name": "Exitos de Vitico Castillo",
                "released": "2017-12-05",
                 "tracks": 20,
                 "cover": {
                     "height": 640,
                     "width": 640,
                     "url": "https://i.scdn.co/image/ab67616d0000b273b4772f702e8ec61c25dd2664"
                 }
         }]');

        return $this->respondWithData($response);
    }
}