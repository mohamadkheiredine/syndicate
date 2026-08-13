<?php

namespace App\Http\Controllers\Cms\Base;

use App\Helpers\FilesHelper;
use App\Http\Controllers\Controller;
use App\Models\SyndicateLogo;
use Illuminate\Http\Request;

class LogoController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:logo-edit', ['only' => ['edit', 'update']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'Logo',
            'link' => 'logo'
        ];
        return $page_info;
    }

    /**
     * Show the form for editing the logo
     *
     */
    public function edit()
    {
        $page_info = $this->page_info();

        $row = SyndicateLogo::findOrFail(1);

        return view('cms.base.'.$page_info['link'].'.edit', compact('page_info', 'row'));
    }

    /**
     * Update the logo
     *
     */
    public function update(Request $request)
    {
        $page_info = $this->page_info();

        $row = SyndicateLogo::findOrFail(1);

        $this->validate($request, [
            'logo' => 'mimes:png,jpg,jpeg,gif|max:500'
        ]);

        $logo_path = $row->getAttributes()['logo'];
        if($request->logo){
            $logo_path = FilesHelper::storeFile($page_info['link'], $request->logo);
        }

        $row->update([
            'logo' => $logo_path
        ]);

        return redirect()->back()->withStatus('Record successfully updated.');
    }
}
