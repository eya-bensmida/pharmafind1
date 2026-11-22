
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon profil pharmacie | PharmaFind</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #F5F8FA;
            font-family: Arial, sans-serif;
            color: #243746;
        }

        .navbar {
            background: #123B4A;
        }

        .navbar-brand {
            color: white;
            font-weight: 800;
        }

        .navbar-brand span {
            color: #16A6A0;
        }

        .main-container {
            max-width: 900px;
            margin: 45px auto;
            padding: 0 15px;
        }

        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 25px rgba(0,0,0,.05);
        }

        .page-title {
            color: #123B4A;
            font-weight: 800;
        }

        .form-label {
            font-weight: 600;
            font-size: 14px;
        }

        .form-control {
            padding: 12px;
            border-radius: 8px;
        }

        .btn-save {
            background: #16A6A0;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
        }

        .btn-save:hover {
            background: #128D88;
            color: white;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
            Pharma<span>Find</span>
        </a>

        <div class="d-flex align-items-center gap-3">
            <span class="text-white">
                <i class="bi bi-hospital"></i>
                Espace pharmacien
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-light btn-sm">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="main-container">

    <div class="mb-4">
        <h2 class="page-title">Mon profil pharmacie</h2>
        <p class="text-muted">
            Consultez et modifiez les informations de votre pharmacie.
        </p>
    </div>

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

    <div class="profile-card">

        <div class="mb-4">
            <h5 class="fw-bold">
                <i class="bi bi-hospital me-2"></i>
                Informations de la pharmacie
            </h5>
            <hr>
        </div>

        <form method="POST" action="{{ route('pharmacien.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nom de la pharmacie</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name', $pharmacy->name) }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Adresse</label>
                <input type="text"
                       name="address"
                       class="form-control"
                       value="{{ old('address', $pharmacy->address) }}"
                       required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ville</label>
                    <input type="text"
                           name="city"
                           class="form-control"
                           value="{{ old('city', $pharmacy->city) }}"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Gouvernorat</label>
                    <input type="text"
                           name="region"
                           class="form-control"
                           value="{{ old('region', $pharmacy->region) }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Téléphone de la pharmacie</label>
                <input type="text"
                       name="phone"
                       class="form-control"
                       value="{{ old('phone', $pharmacy->phone) }}">
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-circle me-2"></i>
                    Enregistrer les modifications
                </button>
            </div>

        </form>
    </div>

</div>

</body>
</html>
