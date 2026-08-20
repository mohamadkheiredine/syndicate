<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SyndicateFamily;

class MembersController extends Controller
{
    private function formatMember(SyndicateFamily $member)
    {
        return [
            'id' => $member->id,
            'name' => $member->name,
            'main_image' => $member->main_image,
            'designation' => $member->designation,
            'syndicate_year' => $member->syndicate_year,
        ];
    }

    public function index()
    {
        $members = SyndicateFamily::where('status', '1')->paginate(8);

        $members->getCollection()->transform(function ($member) {
            return $this->formatMember($member);
        });

        $response = $members->toArray();
        unset($response['links'], $response['first_page_url'], $response['last_page_url'], $response['path']);

        return parent::return_success($response);
    }
}
