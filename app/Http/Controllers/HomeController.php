<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home.index', [
            'testimonials' => Testimonial::query()->where('is_published', true)->orderBy('order')->take(6)->get(),
            'faqs' => Faq::query()->where('is_published', true)->orderBy('order')->take(8)->get(),
            'plans' => config('foodlab.plans'),
        ]);
    }
}
