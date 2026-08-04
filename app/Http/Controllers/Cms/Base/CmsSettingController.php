<?php

namespace App\Http\Controllers\Cms\Base;
use App\Helpers\FilesHelper;
use App\Http\Controllers\Controller;
use App\Models\CmsSetting;
use Illuminate\Http\Request;

class CmsSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:cms_settings-edit', ['only' => ['edit', 'update']]);
    }

    public function page_info()
    {
        $page_info = [
            'title' => 'CMS Settings',
            'link' => 'cms-settings'
        ];
        return $page_info;
    }

    /**
     * Show the form for editing the specified row
     *
     */
    public function edit()
    {
        $page_info = $this->page_info();

        $row = CmsSetting::findOrFail(1);

        return view('cms.base.'.$page_info['link'].'.edit', compact('page_info', 'row'));
    }

    /**
     * Update the specified row in the database
     *
     */
    public function update(Request $request)
    {
        $page_info = $this->page_info();

        $row = CmsSetting::findOrFail(1);

        $this->validate($request, [
            'logo' => 'mimes:png,jpg,jpeg,svg|max:500',
            'primary_color' => 'required'
        ]);

        // Check if the logo exists
        $logo_path = $row->getAttributes()['logo'];
        if($request->logo){
            $logo_path = FilesHelper::storeFile($page_info['link'], $request->logo);
        }

        $row->update([
            'logo' => $logo_path,
            'primary_color' => $request->primary_color
        ]);

        return redirect()->back()->withStatus('Record successfully updated.');
    }

}
