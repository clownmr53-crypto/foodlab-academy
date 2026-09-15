<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public const SUBJECT_PRESETS = [
        'tastebox' => 'Question TasteBox',
        'formation' => 'Formation FoodLab Academy',
        'partenariat' => 'Partenariat',
        'technique' => 'Support technique',
        'autre' => 'Autre',
    ];

    public function create(): View
    {
        return view('home.contact', [
            'subjectPresets' => self::SUBJECT_PRESETS,
            'whatsappUrl' => $this->whatsappUrl(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:40'],
            'country' => ['nullable', 'string', 'max:100'],
            'subject' => ['nullable', 'string', 'max:255'],
            'subject_preset' => ['nullable', 'string', 'in:'.implode(',', array_keys(self::SUBJECT_PRESETS))],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        if (empty($data['subject']) && ! empty($data['subject_preset'])) {
            $data['subject'] = self::SUBJECT_PRESETS[$data['subject_preset']];
        }
        unset($data['subject_preset']);

        $message = ContactMessage::create($data);
        $to = config('mail.from.address');
        if ($to) {
            Mail::to($to)->send(new ContactMessageMail($message));
        }

        return back()->with('status', 'Message envoyé. Nous vous répondrons rapidement.');
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
