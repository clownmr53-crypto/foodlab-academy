<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Administration</h2></x-slot>
    <div class="max-w-6xl mx-auto py-8 px-4 space-y-6">
        @include('admin._nav')
        <div class="grid md:grid-cols-4 gap-4">
            <div class="bg-white border rounded-xl p-4"><p class="text-sm text-slate-500">Utilisateurs</p><p class="text-2xl font-bold">{{ $usersCount }}</p></div>
            <div class="bg-white border rounded-xl p-4"><p class="text-sm text-slate-500">Paiements</p><p class="text-2xl font-bold">{{ $paymentsCount }}</p></div>
            <div class="bg-white border rounded-xl p-4"><p class="text-sm text-slate-500">Modules</p><p class="text-2xl font-bold">{{ $modulesCount }}</p></div>
            <div class="bg-white border rounded-xl p-4"><p class="text-sm text-slate-500">Messages non lus</p><p class="text-2xl font-bold">{{ $unreadContacts }}</p></div>
        </div>
    </div>
</x-app-layout>
