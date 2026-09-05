<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FilesHelper;
use App\Http\Controllers\Controller;
use App\Models\SyndicateActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ActivitiesController extends Controller
{

    private function serializeActivity(SyndicateActivity $activity)
    {
        $raw = $activity->getAttributes();

        $title = empty($raw['title']) ? null : $raw['title'];
        $shortDescription = empty($raw['short_description']) ? null : $raw['short_description'];
        $description = html_entity_decode($raw['description'] ?? '');

  
        $postDate = $raw['post_date'] ?? null;
        $postDate = $postDate ? Carbon::parse($postDate, 'Asia/Beirut')->timestamp : null;

        $images = $activity->gallery->map(function ($g) {
            return [
                'gallery_id' => $g->gallery_id,
                'gallery_image' => $g->gallery_image,
                'image_big' => $g->getAttributes()['image_big']
                    ? FilesHelper::getImageFullUrl('activity_gallery/' . $g->getAttributes()['image_big'])
                    : null,
            ];
        })->values();

        if ($images->isEmpty()) {
            $images = collect([['image_big' => $activity->main_image]]);
        }

        return [
            'id' => $activity->id,
            'title' => $title,
            'post_date' => $postDate,
            'place' => $activity->place,
            'main_image' => $activity->main_image,
            'any_file' => $activity->any_file,
            'short_description' => $shortDescription,
            'description' => $description,
            'url' => route('web.activities.show', $activity->id),
            'images' => $images,
        ];
    }

    public function search(Request $request)
    {
        $activities = SyndicateActivity::with('gallery')
            ->where('status', '1')
            ->whereRaw('LOWER(title) LIKE ?', [strtolower(trim($request->search)) . '%'])
            ->orderBy('post_date', 'desc')
            ->get();

        return parent::return_success($activities->map(function ($activity) {
            return $this->serializeActivity($activity);
        })->values());
    }

    public function index(Request $request)
    {
        if ($request->has('activity_id')) {
            $activity = SyndicateActivity::with('gallery')->find($request->activity_id);

            if (!$activity) {
                return parent::return_error('Invalid Activity', 407, 'messages.invalid_parameter');
            }

            return parent::return_success($this->serializeActivity($activity));
        }

        $activities = SyndicateActivity::with('gallery')
            ->where('status', '1')
            ->orderBy('post_date', 'desc')
            ->paginate(3);

        $activities->getCollection()->transform(function ($activity) {
            return $this->serializeActivity($activity);
        });

        $response = $activities->toArray();
        unset($response['links'], $response['first_page_url'], $response['last_page_url'], $response['path']);

        return parent::return_success($response);
    }
}
