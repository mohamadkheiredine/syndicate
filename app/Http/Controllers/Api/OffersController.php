<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SyndicateOffer;
use Illuminate\Http\Request;

class OffersController extends Controller
{
    private function serializeOffer(SyndicateOffer $offer)
    {
        $raw = $offer->getAttributes();

        $title = empty($raw['title']) ? null : $raw['title'];
        $place = empty($raw['place']) ? null : $raw['place'];
        $offers = empty($raw['offers']) ? null : $raw['offers'];
        $shortDescription = empty($raw['short_description']) ? null : $raw['short_description'];
        $description = html_entity_decode($raw['description'] ?? '');

        return [
            'id' => $offer->id,
            'title' => $title,
            'main_image' => $offer->main_image,
            'new_image' => $offer->new_image,
            'pdf' => $offer->pdf,
            'start_date' => $offer->start_date ? $offer->start_date->timestamp : null,
            'end_date' => $offer->end_date ? $offer->end_date->timestamp : null,
            'place' => $place,
            'offers' => $offers,
            'short_description' => $shortDescription,
            'description' => $description,
            'url' => route('web.offers.show', $offer->id),
        ];
    }

    public function index(Request $request)
    {
        $filter = 'newest';
        if ($request->has('filter')) {
            if (!in_array($request->filter, ['newest', 'alphabetical'])) {
                return parent::return_error('Invalid Filter Type', 402, 'messages.invalid_parameter');
            }
            $filter = $request->filter;
        }

        // Same as ActivitiesController@index's activity_id path - a
        // direct by-id lookup bypasses the published-status filter in
        // old, so it does here too. Only the list/search queries below
        // are scoped to status='1'.
        if ($request->has('offer_id')) {
            $offer = SyndicateOffer::find($request->offer_id);

            if (!$offer) {
                return parent::return_error('Invalid Offer', 407, 'messages.invalid_parameter');
            }

            return parent::return_success($this->serializeOffer($offer));
        }

        $query = SyndicateOffer::where('status', '1');
        $query = $filter === 'newest'
            ? $query->orderBy('id', 'desc')
            : $query->orderByRaw('LOWER(title) ASC');

        $offers = $query->paginate(3);

        $offers->getCollection()->transform(function ($offer) {
            return $this->serializeOffer($offer);
        });

        $response = $offers->toArray();
        unset($response['links'], $response['first_page_url'], $response['last_page_url'], $response['path']);

        return parent::return_success($response);
    }

    public function search(Request $request)
    {
        $search = $request->search;

        $offers = SyndicateOffer::where('status', '1')
            ->whereRaw('LOWER(title) LIKE ?', [strtolower(trim($search)) . '%'])
            ->orWhere('offers', 'like', '%' . $search . '%')
            ->orderBy('end_date', 'asc')
            ->get();

        return parent::return_success($offers->map(function ($offer) {
            return $this->serializeOffer($offer);
        })->values());
    }
}
