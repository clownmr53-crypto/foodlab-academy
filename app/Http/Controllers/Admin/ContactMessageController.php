<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(): View
    {
        $messages = ContactMessage::latest()->paginate(20);

        return view('admin.contact.index', compact('messages'));
    }

    public function show(ContactMessage $contact): View
    {
        if (! $contact->read_at) {
            $contact->update(['read_at' => now()]);
        }

        return view('admin.contact.show', ['message' => $contact]);
    }

    public function destroy(ContactMessage $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('admin.contact.index')->with('status', 'Message supprimé.');
    }
}
