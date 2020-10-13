<?php

namespace App\Application\Actions\Album;

use App\Application\Actions\Action;
use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Middleware\Spotify;
use Psr\Http\Message\ResponseInterface as Response;

class ListAlbumsAction extends Action
{

    protected function action(): Response
    {
        $queryParams = $this->request->getQueryParams();
        if (!$this->isValidArtistName($queryParams)) {
            return $this->respond(
                new ActionPayload(
                    400,
                    new ActionError(ActionError::BAD_REQUEST, "Param 'q' es required, you must specify artist name")
                )
            );
        }

        try {
            $spotify = new Spotify();
            $responseData = $spotify->getAlbumsFromArtist($queryParams['q']);
        } catch (\Exception $e) {
            return $this->respond(new ActionPayload(500));
        }

        if (empty($responseData)) {
            return $this->respond(new ActionPayload(204));
        }

        return $this->respondWithData($responseData);
    }

    protected function isValidArtistName(array $queryParams): bool
    {
        return isset($queryParams['q']) && !empty(trim($queryParams['q']));
    }
}