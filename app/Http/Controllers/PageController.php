<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $settings = SiteSetting::current();

        return view('pages.about', compact('settings'));
    }

    public function contact(): View
    {
        $settings = SiteSetting::current();

        return view('pages.contact', compact('settings'));
    }

    public function privacy(): View
    {
        $settings = SiteSetting::current();

        return view('pages.privacy', compact('settings'));
    }

    public function terms(): View
    {
        $settings = SiteSetting::current();

        return view('pages.terms', compact('settings'));
    }
}
