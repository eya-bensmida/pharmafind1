<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Espace pharmacien - PharmaFind')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --navy: #123B4A;
            --navy-dark: #0D303D;
            --teal: #16A6A0;
            --teal-dark: #128C87;
            --background: #F5F8FA;
            --text: #243746;
            --muted: #82919B;
            --line: #E8EEF1;
            --warn: #F5A524;
            --danger: #E5484D;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text);
        }

        /* ---------- Sidebar (identique à l'admin) ---------- */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: linear-gradient(180deg, var(--navy-dark) 0%, #0A2833 100%);
            color: white;
            padding: 28px 18px 20px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .brand {
            font-size: 23px;
            font-weight: 800;
            margin-bottom: 34px;
            padding-left: 12px;
        }

        .brand span { color: var(--teal); }

        .menu-label {
            color: #91A8B1;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin: 22px 12px 12px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #D5E1E5;
            text-decoration: none;
            padding: 13px 14px;
            border-radius: 10px;
            margin-bottom: 6px;
            font-size: 14px;
            transition: .22s;
            position: relative;
        }

        .sidebar a i { font-size: 17px; transition: .22s; }

        .sidebar a:hover {
            background: rgba(22,166,160,.14);
            color: white;
            transform: translateX(3px);
        }

        .sidebar a.active {
            background: linear-gradient(90deg, rgba(22,166,160,.28), rgba(22,166,160,.08));
            color: white;
            font-weight: 600;
        }

        .sidebar a.active::before {
            content: "";
            position: absolute;
            left: -18px;
            top: 10px;
            bottom: 10px;
            width: 4px;
            border-radius: 0 4px 4px 0;
            background: var(--teal);
            box-shadow: 0 0 12px var(--teal);
        }

        .sidebar a.active i { color: #5fe0d6; }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            background: transparent;
            border: none;
            color: #D5E1E5;
            padding: 13px 14px;
            border-radius: 10px;
            font-size: 14px;
            text-align: left;
            transition: .22s;
        }

        .logout-btn:hover { background: rgba(229,72,77,.18); color: #fff; }

        .side-card {
            margin-top: auto;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 14px;
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--teal), #3fd0c9);
            color: #fff;
            font-weight: 800;
            display: grid;
            place-items: center;
        }

        .side-card .name {
            font-size: 13px;
            font-weight: 700;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 130px;
        }

        .side-card .state { font-size: 11px; color: #9fd9d4; }

        .dot-live {
            display: inline-block;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #3fe0a0;
            margin-right: 5px;
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(63,224,160,.6); }
            70% { box-shadow: 0 0 0 7px rgba(63,224,160,0); }
            100% { box-shadow: 0 0 0 0 rgba(63,224,160,0); }
        }

        /* ---------- Structure ---------- */
        .main { margin-left: 250px; min-height: 100vh; }

        .topbar {
            height: 76px;
            background: rgba(255,255,255,.9);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 35px;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .content { padding: 35px; }

        .page-title { font-size: 25px; font-weight: 800; margin-bottom: 6px; color: var(--navy); }
        .page-subtitle { color: var(--muted); font-size: 13px; }

        /* ---------- Composants ---------- */
        .pf-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 6px 24px rgba(18,59,74,.06);
            transition: transform .25s, box-shadow .25s;
        }

        .pf-card.hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 36px rgba(18,59,74,.12);
        }

        .card-title-pf {
            font-size: 15px;
            font-weight: 700;
            color: var(--navy);
            margin: 0;
        }

        .btn-teal {
            background: var(--teal);
            color: white;
            border: none;
            font-weight: 600;
            border-radius: 11px;
            padding: 11px 20px;
            box-shadow: 0 6px 16px rgba(22,166,160,.28);
            transition: .22s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-teal:hover:not(:disabled) {
            background: var(--teal-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(22,166,160,.36);
        }

        .btn-teal:disabled { background: #B8C6CC; box-shadow: none; cursor: not-allowed; }

        .btn-soft {
            background: rgba(22,166,160,.1);
            color: var(--teal-dark);
            border: none;
            font-weight: 600;
            border-radius: 11px;
            padding: 11px 20px;
            transition: .22s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-soft:hover { background: rgba(22,166,160,.2); color: var(--teal-dark); }

        .btn-glass {
            background: rgba(255,255,255,.15);
            color: #fff;
            border: 1px solid rgba(255,255,255,.35);
            font-weight: 600;
            border-radius: 11px;
            padding: 11px 20px;
            transition: .22s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-glass:hover { background: rgba(255,255,255,.28); color: #fff; }

        .form-control, .form-select {
            border-radius: 11px;
            border: 1px solid #D9E3E8;
            padding: 12px 14px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 .22rem rgba(22,166,160,.16);
        }

        .form-label { font-weight: 600; font-size: 13px; color: var(--navy); }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 11px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .pill-ok { background: rgba(22,166,160,.14); color: #0e7f7a; }
        .pill-low { background: rgba(245,165,36,.18); color: #9a6200; }
        .pill-out { background: rgba(229,72,77,.14); color: #b42328; }
        .pill-info { background: rgba(18,59,74,.08); color: var(--navy); }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            font-size: 22px;
        }

        .ic-teal { background: rgba(22,166,160,.14); color: var(--teal); }
        .ic-navy { background: rgba(18,59,74,.1); color: var(--navy); }
        .ic-warn { background: rgba(245,165,36,.18); color: #c47f00; }
        .ic-danger { background: rgba(229,72,77,.14); color: var(--danger); }

        .pf-table thead th {
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--muted);
            background: #F8FBFC;
            border-bottom: 1px solid var(--line);
            padding: 14px 12px;
            white-space: nowrap;
        }

        .pf-table td { padding: 14px 12px; border-color: var(--line); vertical-align: middle; }
        .pf-table tbody tr { transition: background .18s; }
        .pf-table tbody tr:hover { background: #F6FBFB; }

        /* Animation d'apparition */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: none; }
        }

        .fade-up {
            opacity: 0;
            animation: fadeUp .6s cubic-bezier(.2,.7,.2,1) forwards;
            animation-delay: calc(var(--d, 0) * 90ms);
        }

        @media (prefers-reduced-motion: reduce) {
            .fade-up { animation: none; opacity: 1; }
            .dot-live { animation: none; }
        }

        /* Notifications */
        #toasts {
            position: fixed;
            top: 90px;
            right: 22px;
            z-index: 3000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .pf-toast {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 260px;
            max-width: 380px;
            background: #fff;
            border-left: 5px solid var(--teal);
            border-radius: 12px;
            padding: 13px 16px;
            box-shadow: 0 12px 32px rgba(18,59,74,.18);
            font-size: 14px;
            transform: translateX(130%);
            transition: transform .35s cubic-bezier(.2,.8,.2,1);
        }

        .pf-toast.show { transform: none; }
        .pf-toast i { font-size: 20px; color: var(--teal); }
        .pf-toast.danger { border-left-color: var(--danger); }
        .pf-toast.danger i { color: var(--danger); }
        .pf-toast.warn { border-left-color: var(--warn); }
        .pf-toast.warn i { color: var(--warn); }

        @media(max-width: 768px) {
            .sidebar { width: 75px; padding: 20px 8px; }
            .brand { font-size: 13px; padding-left: 5px; }
            .sidebar a { justify-content: center; font-size: 0; }
            .sidebar a i { font-size: 20px; }
            .sidebar a.active::before { left: -8px; }
            .menu-label { display: none; }
            .side-card { display: none; }
            .logout-btn { justify-content: center; font-size: 0; }
            .logout-btn i { font-size: 20px; margin: 0; }
            .main { margin-left: 75px; }
            .content { padding: 20px; }
            .topbar { padding: 0 20px; }
            .topbar .who { display: none; }
        }
    </style>

    @stack('styles')
</head>

<body>

@php
    $navUser = auth()->user();
    $navPharmacy = $navUser->pharmacy ?? null;
    $initial = mb_strtoupper(mb_substr($navPharmacy->name ?? $navUser->name ?? 'P', 0, 1));
@endphp

<aside class="sidebar">

    <div class="brand">
        Pharma<span>Find</span>
    </div>

    <div class="menu-label">Menu principal</div>

    <a href="{{ route('pharmacien.dashboard') }}"
       class="{{ request()->routeIs('pharmacien.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid"></i>
        Tableau de bord
    </a>

    <a href="{{ route('pharmacien.medicaments.index') }}"
       class="{{ request()->routeIs('pharmacien.medicaments.index') || request()->routeIs('pharmacien.medicaments.edit') ? 'active' : '' }}">
        <i class="bi bi-capsule"></i>
        Mon stock
    </a>

    <a href="{{ route('pharmacien.medicaments.create') }}"
       class="{{ request()->routeIs('pharmacien.medicaments.create') ? 'active' : '' }}">
        <i class="bi bi-plus-circle"></i>
        Ajouter un médicament
    </a>

    <div class="menu-label">Ma pharmacie</div>

    <a href="{{ route('pharmacien.profile.edit') }}"
       class="{{ request()->routeIs('pharmacien.profile.*') ? 'active' : '' }}">
        <i class="bi bi-hospital"></i>
        Mon profil
    </a>

    <a href="{{ url('/') }}" target="_blank" rel="noopener">
        <i class="bi bi-search-heart"></i>
        Voir le site public
    </a>

    <div class="menu-label">Compte</div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">
            <i class="bi bi-box-arrow-left"></i>
            Déconnexion
        </button>
    </form>

    <div class="side-card">
        <div class="avatar">{{ $initial }}</div>
        <div>
            <div class="name">{{ $navPharmacy->name ?? 'Ma pharmacie' }}</div>
            <div class="state"><span class="dot-live"></span>En ligne</div>
        </div>
    </div>

</aside>

<main class="main">

    <header class="topbar">
        <strong>@yield('topbar', 'Espace pharmacien')</strong>

        <div class="small text-muted who">
            <i class="bi bi-person-circle me-2"></i>
            {{ $navUser->name ?? 'Pharmacien' }}
        </div>
    </header>

    <section class="content">

        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm">
                <i class="bi bi-x-circle-fill me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @yield('content')

    </section>

</main>

<div id="toasts" aria-live="polite"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Notification flottante : toast('Message', 'success' | 'danger' | 'warn')
    window.toast = function (message, type) {
        type = type || 'success';
        const icons = { success: 'bi-check-circle-fill', danger: 'bi-x-circle-fill', warn: 'bi-exclamation-triangle-fill' };
        const box = document.getElementById('toasts');
        const t = document.createElement('div');
        t.className = 'pf-toast ' + (type === 'success' ? '' : type);
        t.innerHTML = '<i class="bi ' + (icons[type] || icons.success) + '"></i><span></span>';
        t.querySelector('span').textContent = message;
        box.appendChild(t);
        requestAnimationFrame(function () { t.classList.add('show'); });
        setTimeout(function () {
            t.classList.remove('show');
            setTimeout(function () { t.remove(); }, 400);
        }, 3600);
    };

    // Compteur animé : animateCount(element, valeurFinale)
    window.animateCount = function (el, target) {
        const start = parseInt(el.textContent.replace(/\s/g, ''), 10) || 0;
        const t0 = performance.now();
        const duration = 800;
        function tick(now) {
            const p = Math.min((now - t0) / duration, 1);
            const eased = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(start + (target - start) * eased).toLocaleString('fr-FR');
            if (p < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    };

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-count]').forEach(function (el) {
            el.textContent = '0';
            window.animateCount(el, parseInt(el.dataset.count, 10) || 0);
        });
    });
</script>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            toast(@json(session('success')), 'success');
        });
    </script>
@endif

@stack('scripts')

</body>
</html>
