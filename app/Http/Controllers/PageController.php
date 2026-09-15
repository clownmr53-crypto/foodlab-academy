<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('home.about', [
            'whatsappUrl' => $this->whatsappUrl(),
        ]);
    }

    public function tastebox(): View
    {
        return view('home.tastebox', [
            'price' => (int) config('foodlab.tastebox.price', 15000),
            'currencyLabel' => config('foodlab.tastebox.currency_label', 'FCFA'),
            'guaranteeDays' => (int) config('foodlab.tastebox.guarantee_days', 7),
            'whatsappUrl' => $this->whatsappUrl(),
            'paymentMethods' => config('foodlab.payment_methods_labels', []),
        ]);
    }

    protected function whatsappUrl(): ?string
    {
        $url = config('foodlab.whatsapp_url');
        $number = preg_replace('/\D+/', '', (string) config('foodlab.whatsapp_number'));
        if (filled($url)) {
            return $url;
        }
        if (filled($number)) {
            return 'https://wa.me/'.$number;
        }

        return null;
    }
}
