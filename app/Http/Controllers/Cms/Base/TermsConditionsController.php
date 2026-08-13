<?php

namespace App\Http\Controllers\Cms\Base;

use App\Http\Controllers\Controller;
use App\Models\SyndicateTermsCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TermsConditionsController extends Controller
{
    // All three pages share the same table (syndicate_terms_conditions),
    // told apart only by row id - matches the old site exactly, which used
    // three near-identical admin pages against the same table for this
    // reason.
    private $pages = [
        'terms-conditions' => ['id' => 1, 'title' => 'Terms & Conditions'],
        'rules' => ['id' => 2, 'title' => 'Rules'],
        'education' => ['id' => 3, 'title' => 'Education'],
    ];

    function __construct()
    {
        $this->middleware('permission:terms_conditions-edit', ['only' => ['edit', 'update']]);
    }

    public function page_info($page)
    {
        $page_info = [
            'title' => $this->pages[$page]['title'],
            'link' => 'terms-conditions',
            'page' => $page,
        ];
        return $page_info;
    }

    /**
     * Show the form for editing the row
     *
     */
    public function edit($page)
    {
        $page_info = $this->page_info($page);

        $row = SyndicateTermsCondition::findOrFail($this->pages[$page]['id']);

        return view('cms.base.terms-conditions.edit', compact('page_info', 'row'));
    }

    /**
     * Update the row in the database
     *
     */
    public function update(Request $request, $page)
    {
        $page_info = $this->page_info($page);

        $row = SyndicateTermsCondition::findOrFail($this->pages[$page]['id']);

        $this->validate($request, [
            'description' => 'required|string',
        ]);

        // Only a publisher gets a say in this at all - and even then it's
        // their choice via the checkbox, not automatic. Anyone else's edit
        // always goes back to pending, even if it was already live, until
        // someone with terms_conditions-publish approves it again.
        $canPublish = Auth::guard('admin')->user()->can('terms_conditions-publish');
        $published = $canPublish && $request->boolean('publish_status');

        $row->update([
            'description' => $request->description,
            'publish_status' => $published ? 1 : 0,
        ]);

        return redirect()->back()->withStatus($page_info['title'] . ' successfully updated.');
    }

}
