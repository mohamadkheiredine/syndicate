<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{

	public function index()
	{
		$page_title = 'Home';

		return view('web.pages.home', compact(
			'page_title'
		));
	}

}
