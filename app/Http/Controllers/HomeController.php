<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Module;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home.index', [
            'testimonials' => Testimonial::query()->where('is_published', true)->orderBy('order')->take(6)->get(),
            'faqs' => Faq::query()->where('is_published', true)->orderBy('order')->take(12)->get(),
            'modules' => Module::query()->where('is_published', true)->orderBy('order')->get(),
            'plans' => config('foodlab.plans'),
            'paymentMethods' => config('foodlab.payment_methods_labels', []),
            'guaranteeDays' => (int) config('foodlab.guarantee_days', 14),
        ]);
    }
}
