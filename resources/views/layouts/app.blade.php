<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Extrack' }} · {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-100 font-sans text-slate-900 antialiased">
    <div class="min-h-full lg:flex">
        <aside class="hidden w-64 shrink-0 flex-col bg-slate-900 px-4 py-6 text-slate-300 lg:flex">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-2">
                <span class="flex size-10 items-center justify-center rounded-xl bg-emerald-500 text-lg font-bold text-white">$</span>
                <span>
                    <span class="block text-lg font-semibold leading-tight text-white">Extrack</span>
                    <span class="block text-xs text-slate-400">Personal finance</span>
                </span>
            </a>

            <nav class="mt-8 flex flex-1 flex-col gap-1">
                @php
                    $navigation = [
                        ['label' => 'Dashboard', 'route' => 'dashboard', 'match' => 'dashboard', 'icon' => 'M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10'],
                        ['label' => 'Transactions', 'route' => 'transactions.index', 'match' => 'transactions.*', 'icon' => 'M4 7h16M4 12h16M4 17h10'],
                        ['label' => 'Debts', 'route' => 'debts.index', 'match' => 'debts.*', 'icon' => 'M12 3v18m5-14H9a3 3 0 000 6h6a3 3 0 010 6H7'],
                        ['label' => 'Categories', 'route' => 'categories.index', 'match' => 'categories.*', 'icon' => 'M4 5h7v7H4zM13 5h7v7h-7zM4 14h7v7H4zM13 14h7v7h-7z'],
                    ];
                @endphp

                @foreach ($navigation as $item)
                    <a href="{{ route($item['route']) }}"
                       @class([
                           'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition',
                           'bg-slate-800 text-white' => request()->routeIs($item['match']),
                           'text-slate-400 hover:bg-slate-800/60 hover:text-white' => ! request()->routeIs($item['match']),
                       ])>
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <a href="{{ route('transactions.create') }}"
               class="mt-6 flex items-center justify-center gap-2 rounded-lg bg-emerald-500 px-3 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-400">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" d="M12 5v14M5 12h14" />
                </svg>
                Add transaction
            </a>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="border-b border-slate-200 bg-white lg:hidden">
                <div class="flex items-center justify-between px-4 py-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-semibold">
                        <span class="flex size-8 items-center justify-center rounded-lg bg-emerald-500 text-sm font-bold text-white">$</span>
                        Extrack
                    </a>
                    <a href="{{ route('transactions.create') }}" class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white">Add</a>
                </div>
                <nav class="flex gap-1 overflow-x-auto px-3 pb-3">
                    @foreach ($navigation as $item)
                        <a href="{{ route($item['route']) }}"
                           @class([
                               'whitespace-nowrap rounded-lg px-3 py-1.5 text-xs font-medium',
                               'bg-slate-900 text-white' => request()->routeIs($item['match']),
                               'text-slate-600 hover:bg-slate-100' => ! request()->routeIs($item['match']),
                           ])>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </header>

            <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                @if (session('status'))
                    <div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
