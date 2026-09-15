<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Module;
use App\Models\Payment;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'usersCount' => User::count(),
            'paymentsCount' => Payment::where('status', 'paid')->count(),
            'modulesCount' => Module::count(),
            'unreadContacts' => ContactMessage::whereNull('read_at')->count(),
        ]);
    }
}
