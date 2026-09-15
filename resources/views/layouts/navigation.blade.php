<nav x-data="{ open: false }" class="bg-white border-b border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="font-bold text-emerald-700 text-lg">FoodLab Academy</a>
                @auth
                    <div class="hidden sm:flex gap-4 text-sm">
                        <a href="{{ route('dashboard') }}" class="hover:text-emerald-700">Tableau de bord</a>
                        @if(auth()->user()->hasPlan('starter') || auth()->user()->isAdmin())
                            <a href="{{ route('lms.index') }}" class="hover:text-emerald-700">Formation</a>
                            <a href="{{ route('calculator.index') }}" class="hover:text-emerald-700">Calculateur</a>
                        @endif
                        <a href="{{ route('payments.plans') }}" class="hover:text-emerald-700">Plans</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-700 font-semibold">Admin</a>
                        @endif
                    </div>
                @endauth
            </div>
            <div class="hidden sm:flex items-center gap-3 text-sm">
                @auth
                    <a href="{{ route('profile.edit') }}" class="text-slate-600 hover:text-emerald-700">{{ auth()->user()->name }}</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button class="text-slate-500 hover:text-red-600">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-emerald-700">Connexion</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-emerald-600 text-white px-3 py-1.5 font-medium">Inscription</a>
                @endauth
            </div>
            <div class="sm:hidden flex items-center">
                <button @click="open=!open" class="p-2 text-slate-600">☰</button>
            </div>
        </div>
    </div>
    <div x-show="open" class="sm:hidden border-t px-4 py-3 space-y-2 text-sm" x-cloak>
        @auth
            <a class="block" href="{{ route('dashboard') }}">Tableau de bord</a>
            <a class="block" href="{{ route('payments.plans') }}">Plans</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button>Déconnexion</button></form>
        @else
            <a class="block" href="{{ route('login') }}">Connexion</a>
            <a class="block" href="{{ route('register') }}">Inscription</a>
        @endauth
    </div>
</nav>
