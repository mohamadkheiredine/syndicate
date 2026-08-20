<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FilesHelper;
use App\Http\Controllers\Controller;
use App\Models\SyndicateActivity;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{

    private function formatActivity(SyndicateActivity $activity)
    {
        return [
            'id' => $activity->id,
            'title' => $activity->title,
            'description' => $activity->description,
            'short_description' => $activity->short_description,
            'place' => $activity->place,
            // Link to this activity's page on the site, not a stored DB
            // column (the old table's `url` was empty/unused in practice).
            'url' => route('web.activities.show', $activity->id),
            'main_image' => $activity->main_image,
            'any_file' => $activity->any_file,
            'post_date' => $activity->post_date ? $activity->post_date->timestamp : null,
            'images' => $activity->gallery->map(function ($g) {
                return [
                    'id' => $g->gallery_id,
                    'gallery_image' => $g->gallery_image,
                    'image_big' => $g->getAttributes()['image_big']
                        ? FilesHelper::getImageFullUrl('activities-gallery/' . $g->getAttributes()['image_big'])
                        : null,
                ];
            })->values(),
        ];
    }

    public function index(Request $request)
    {
        if ($request->has('activity_id')) {
            $activity = SyndicateActivity::with('gallery')->find($request->activity_id);

            if (!$activity) {
                return parent::return_error('Invalid Activity', 407, 'messages.invalid_parameter');
            }

            return parent::return_success($this->formatActivity($activity));
        }

        $activities = SyndicateActivity::with('gallery')
            ->where('status', '1')
            ->orderBy('post_date', 'desc')
            ->paginate(3);

        $activities->getCollection()->transform(function ($activity) {
            return $this->formatActivity($activity);
        });

        
        $response = $activities->toArray();
        unset($response['links'], $response['first_page_url'], $response['last_page_url'], $response['path']);

        return parent::return_success($response);
    }
}
