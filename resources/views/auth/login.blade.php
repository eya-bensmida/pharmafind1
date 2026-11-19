
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion | PharmaFind</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, sans-serif;
            background: #F5F8FA;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
        }

        .login-container {
            width: 100%;
            max-width: 1050px;
            min-height: 620px;
            background: white;
            border-radius: 22px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            box-shadow: 0 20px 65px rgba(18,59,74,.09);
        }

        .login-brand {
            background: #123B4A;
            padding: 55px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: #16A6A0;
            display: grid;
            place-items: center;
            font-size: 25px;
        }

        .brand-name { font-size: 24px; font-weight: 800; }

        .brand-content { position: relative; z-index: 1; }

        .brand-content h1 {
            font-size: 38px;
            line-height: 1.3;
            margin-bottom: 20px;
        }

        .brand-content p {
            color: #C2D6DC;
            font-size: 14px;
            line-height: 1.9;
        }

        .brand-footer {
            color: #AFC7CE;
            font-size: 11px;
        }

        .brand-circle {
            position: absolute;
            width: 340px;
            height: 340px;
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 50%;
            right: -130px;
            bottom: -120px;
        }

        .brand-circle::after {
            content: "";
            position: absolute;
            inset: 35px;
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 50%;
        }

        .login-form {
            padding: 65px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-icon {
            width: 48px;
            height: 48px;
            border-radius: 13px;
            background: #E6F6F4;
            color: #16A6A0;
            display: grid;
            place-items: center;
            font-size: 22px;
            margin-bottom: 25px;
        }

        .login-form h2 {
            color: #123B4A;
            font-size: 26px;
            margin: 0 0 10px;
        }

        .subtitle {
            font-size: 13px;
            color: #82919B;
            margin-bottom: 35px;
        }

        .form-group { margin-bottom: 21px; }

        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #354B57;
            margin-bottom: 9px;
        }

        .input-wrapper { position: relative; }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #82919B;
        }

        input {
            width: 100%;
            height: 49px;
            border: 1px solid #E1E9ED;
            border-radius: 9px;
            padding: 0 15px 0 43px;
            font-family: inherit;
            font-size: 13px;
            outline: none;
            transition: .2s;
        }

        input:focus {
            border-color: #16A6A0;
            box-shadow: 0 0 0 3px rgba(22,166,160,.10);
        }

        .submit-button {
            width: 100%;
            height: 50px;
            background: #16A6A0;
            border: none;
            border-radius: 9px;
            color: white;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 12px;
            transition: .2s;
        }

        .submit-button:hover { background: #128D88; }

        .error-message {
            background: #FFF0F0;
            border: 1px solid #FFD8D8;
            color: #B42318;
            padding: 12px;
            border-radius: 8px;
            font-size: 12px;
            margin-bottom: 20px;
        }

        .security-note {
            text-align: center;
            color: #82919B;
            font-size: 11px;
            margin-top: 28px;
        }

        @media(max-width: 750px) {
            .login-container { grid-template-columns: 1fr; }
            .login-brand { display: none; }
            .login-form { padding: 45px 28px; }
        }
        .login-link {
    text-align: center;
    color: #82919B;
    font-size: 12px;
    margin-top: 18px;
}

.login-link a {
    color: #16A6A0;
    font-weight: 700;
    text-decoration: none;
}

.login-link a:hover {
    text-decoration: underline;
}
    </style>
</head>
<body>

<div class="login-container">

    <section class="login-brand">
        <div class="brand-logo">
            <div class="brand-icon">
                <i class="bi bi-capsule"></i>
            </div>
            <div class="brand-name">PharmaFind</div>
        </div>

        <div class="brand-content">
            <h1>La santé connectée, une gestion simplifiée.</h1>
            <p>
                Bienvenue dans la plateforme PharmaFind.
                Gérez les pharmacies, le catalogue des médicaments
                et les informations de disponibilité depuis un
                espace centralisé.
            </p>
        </div>

        <div class="brand-footer">
            © {{ date('Y') }} PharmaFind — Plateforme de gestion pharmaceutique
        </div>

        <div class="brand-circle"></div>
    </section>

    <section class="login-form">
        <div class="form-icon">
            <i class="bi bi-shield-lock"></i>
        </div>

        <h2>Bienvenue</h2>
        <p class="subtitle">
            Connectez-vous à votre espace administrateur.
        </p>
        @if (session('success'))
    <div style="background:#EAF8F1; border:1px solid #C8EBDD; color:#18794E; padding:12px; border-radius:8px; font-size:12px; margin-bottom:20px;">
        <i class="bi bi-check-circle me-1"></i>
        {{ session('success') }}
    </div>
@endif

        @if ($errors->any())
            <div class="error-message">
                <i class="bi bi-exclamation-circle me-1"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <div class="input-wrapper">
                    <i class="bi bi-envelope"></i>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@pharmafind.tn"
                        required
                        autocomplete="username"
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="input-wrapper">
                    <i class="bi bi-lock"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Votre mot de passe"
                        required
                        autocomplete="current-password"
                    >
                </div>
            </div>

            <button type="submit" class="submit-button">
                Se connecter
                <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </form>
        <div class="login-link">
    Vous n'avez pas encore de compte ?
    <a href="{{ route('register') }}">Créer un compte</a>
</div>

        <div class="security-note">
            <i class="bi bi-shield-check"></i>
            Connexion sécurisée — Espace réservé aux administrateurs
        </div>
    </section>

</div>

</body>
</html>
