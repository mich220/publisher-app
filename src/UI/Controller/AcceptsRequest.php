<?php

namespace App\UI\Controller;

use App\Domain\Collections\ParameterBag;

trait AcceptsRequest
{
    protected function acceptRequest(ParameterBag $bag)
    {
        $data = $bag->getParameters();
        $headers = [];

        if ($bag->has('reference_url')) {
            $data['reference_url'] = $this->getReferenceUrl($data['reference_url']);
            $headers = $this->getAcceptHeaders($data['reference_url']);
        }

        return $this->json(
            [
                'status' => 'accepted',
                'redirect_url' => '/api/resource/' . $bag->get('reference_id') . '/status',
                'data' => $data
            ],
            202,
           $headers
        );
    }

    private function getAcceptHeaders(string $refUrl = ''): array
    {
        return  [
            'Location' => $refUrl,
            'Retry-After' => 1 //todo - change to some const or strategy?
        ];
    }

    private function getReferenceUrl(string $refId): string
    {
        return '/api/resource/' . $refId . '/status';
    }
}