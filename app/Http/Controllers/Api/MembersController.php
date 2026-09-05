<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SyndicateFamily;
use Illuminate\Http\Request;

class MembersController extends Controller
{
    private function formatMember(SyndicateFamily $member)
    {
        $raw = $member->getAttributes();

        return [
            'id' => $member->id,
            'name' => empty($raw['name']) ? null : $raw['name'],
            'main_image' => $member->main_image,
            'designation' => empty($raw['designation']) ? null : $raw['designation'],
            'syndicate_year' => empty($raw['syndicate_year']) ? null : $raw['syndicate_year'],
        ];
    }

    public function index(Request $request)
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
