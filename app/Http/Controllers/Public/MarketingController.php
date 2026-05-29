<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\SiteSettingsService;

class MarketingController extends Controller
{
  public function faq(SiteSettingsService $settings)
  {
    return view('public.marketing.faq', ['settings' => $settings->all()]);
  }

  public function contact(SiteSettingsService $settings)
  {
    return view('public.marketing.contact', ['settings' => $settings->all()]);
  }

  public function terms(SiteSettingsService $settings)
  {
    return view('public.marketing.terms', ['settings' => $settings->all()]);
  }

  public function restaurantsList()
  {
    return view('public.marketing.restaurants-list');
  }

  public function templates()
  {
    return view('public.marketing.templates');
  }
}
