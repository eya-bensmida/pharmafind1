
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'PharmaFind Administration')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --navy: #123B4A;
            --navy-dark: #0D303D;
            --teal: #16A6A0;
            --background: #F5F8FA;
            --text: #243746;
            --muted: #82919B;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text);
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: var(--navy-dark);
            color: white;
            padding: 28px 18px;
            z-index: 1000;
        }

        .brand {
            font-size: 23px;
            font-weight: 800;
            margin-bottom: 40px;
            padding-left: 12px;
        }

        .brand span {
            color: var(--teal);
        }

        .menu-label {
            color: #91A8B1;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 24px 12px 12px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #D5E1E5;
            text-decoration: none;
            padding: 13px 14px;
            border-radius: 9px;
            margin-bottom: 6px;
            font-size: 14px;
            transition: .2s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(22,166,160,.18);
            color: white;
        }

        .main {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            height: 76px;
            background: white;
            border-bottom: 1px solid #E8EEF1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 35px;
        }

        .content {
            padding: 35px;
        }

        .page-title {
            font-size: 25px;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .page-subtitle {
            color: var(--muted);
            font-size: 13px;
        }

        .btn-teal {
            background: var(--teal);
            color: white;
            border: none;
        }

        .btn-teal:hover {
            background: #128C87;
            color: white;
        }

        @media(max-width: 768px) {
            .sidebar {
                width: 75px;
                padding: 20px 8px;
            }

            .brand {
                font-size: 13px;
                padding-left: 5px;
            }

            .sidebar a {
                justify-content: center;
                font-size: 0;
            }

            .sidebar a i {
                font-size: 20px;
            }

            .menu-label {
                display: none;
            }

            .main {
                margin-left: 75px;
            }

            .content {
                padding: 20px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<aside class="sidebar">

    <div class="brand">
        Pharma<span>Find</span>
    </div>

    <div class="menu-label">Menu principal</div>

    <a href="{{ route('admin.dashboard') }}"
       class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid"></i>
        Vue d'ensemble
    </a>

    <div class="menu-label">Administration</div>

    <a href="{{ route('admin.pharmacies.index') }}"
       class="{{ request()->routeIs('admin.pharmacies.*') ? 'active' : '' }}">
        <i class="bi bi-hospital"></i>
        Pharmacies
    </a>

    <a href="#">
        <i class="bi bi-capsule"></i>
        Médicaments
    </a>

    <a href="#">
        <i class="bi bi-clipboard-check"></i>
        Propositions
    </a>

   <a href="{{ route('admin.failed-searches.index') }}"
   class="{{ request()->routeIs('admin.failed-searches.*') ? 'active' : '' }}">
    <i class="bi bi-search"></i>
    Recherches sans résultat
</a>
    <a href="{{ route('admin.users.index') }}"
   class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
    <i class="bi bi-people"></i>
    Utilisateurs
</a>
    <div class="menu-label">Compte</div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit" class="btn text-start w-100 text-white">
            <i class="bi bi-box-arrow-left me-2"></i>
            Déconnexion
        </button>
    </form>

</aside>

<main class="main">

    <header class="topbar">
        <strong>Administration</strong>

        <div class="small text-muted">
            <i class="bi bi-person-circle me-2"></i>
            {{ auth()->user()->name ?? 'Administrateur' }}
        </div>
    </header>

    <section class="content">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        @yield('content')

    </section>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>
