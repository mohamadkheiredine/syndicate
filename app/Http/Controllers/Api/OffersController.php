<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SyndicateOffer;

class OffersController extends Controller
{
    private function formatOffer(SyndicateOffer $offer)
    {
        return [
            'id' => $offer->id,
            'title' => $offer->title,
            'description' => $offer->description,
            'short_description' => $offer->short_description,
            'place' => $offer->place,
            'url' => route('web.offers.show', $offer->id),
            'main_image' => $offer->main_image,
            'new_image' => $offer->new_image,
            'pdf' => $offer->pdf,
            'offers' => $offer->offers,
            'start_date' => $offer->start_date ? $offer->start_date->timestamp : null,
            'end_date' => $offer->end_date ? $offer->end_date->timestamp : null,
        ];
    }

    public function index()
    {
        $offers = SyndicateOffer::where('status', '1')->paginate(3);

        $offers->getCollection()->transform(function ($offer) {
            return $this->formatOffer($offer);
        });

        $response = $offers->toArray();
        unset($response['links'], $response['first_page_url'], $response['last_page_url'], $response['path']);

        return parent::return_success($response);
    }
}
