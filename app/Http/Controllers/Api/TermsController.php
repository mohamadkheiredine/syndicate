<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SyndicateTermsCondition;

/**
 * Ports the old CMS's Api\TermsController exactly - public, no auth.
 * Old's real visible fields are just id + description (its model hides
 * publish_status/publish_description), decoded once via
 * html_entity_decode(). Built as plain arrays here rather than returning
 * the model directly, since the model has no $hidden list (it's shared
 * with the admin CMS form) and its own description accessor also runs an
 * extra stripslashes() pass old's API never applied.
 */
class TermsController extends Controller
{
    public function index()
    {
        $terms = SyndicateTermsCondition::where('publish_status', 1)->get()->map(function ($term) {
            $raw = $term->getAttributes();

            return [
                'id' => $term->id,
                'description' => html_entity_decode($raw['description'] ?? ''),
            ];
        });

        return response($terms, 200);
    }
}
