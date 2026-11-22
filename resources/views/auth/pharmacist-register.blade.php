
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demande de création - PharmaFind</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #F5F8FA;
            font-family: Arial, sans-serif;
        }

        .registration-card {
            max-width: 850px;
            margin: 45px auto;
            background: white;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 10px 40px rgba(18,59,74,.08);
        }

        .section-title {
            color: #123B4A;
            font-weight: 700;
            border-bottom: 1px solid #e9eef1;
            padding-bottom: 12px;
            margin: 30px 0 20px;
        }

        .btn-main {
            background: #16A6A0;
            color: white;
        }

        .btn-main:hover {
            background: #128D88;
            color: white;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="registration-card">

        <div class="text-center mb-4">
            <h2 class="fw-bold" style="color:#123B4A">
                <i class="bi bi-capsule me-2"></i>
                PharmaFind
            </h2>
            <h4>Demande de création d'espace pharmacie</h4>
            <p class="text-muted">
                Remplissez les informations et transmettez vos justificatifs.
            </p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('pharmacien.register.store') }}"
              enctype="multipart/form-data">

            @csrf

            <h5 class="section-title">1. Informations personnelles</h5>

            <div class="mb-3">
                <label class="form-label">Nom et prénom *</label>
                <input type="text" name="full_name"
                       class="form-control"
                       value="{{ old('full_name') }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Adresse e-mail *</label>
                    <input type="email" name="email"
                           class="form-control"
                           value="{{ old('email') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Téléphone *</label>
                    <input type="text" name="phone"
                           class="form-control"
                           value="{{ old('phone') }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mot de passe *</label>
                    <input type="password" name="password"
                           class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirmer le mot de passe *</label>
                    <input type="password" name="password_confirmation"
                           class="form-control" required>
                </div>
            </div>

            <h5 class="section-title">2. Informations de la pharmacie</h5>

            <div class="mb-3">
                <label class="form-label">Nom officiel de la pharmacie *</label>
                <input type="text" name="name"
                       class="form-control"
                       value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Adresse complète *</label>
                <input type="text" name="address"
                       class="form-control"
                       value="{{ old('address') }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ville *</label>
                    <input type="text" name="city"
                           class="form-control"
                           value="{{ old('city') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Gouvernorat</label>
                    <input type="text" name="region"
                           class="form-control"
                           value="{{ old('region') }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Téléphone de la pharmacie *</label>
                <input type="text" name="pharmacy_phone"
                       class="form-control"
                       value="{{ old('pharmacy_phone') }}" required>
            </div>

            <h5 class="section-title">3. Documents justificatifs</h5>

            <div class="alert alert-info">
                Les documents doivent être des justificatifs authentiques
                correspondant à la pharmacie et au responsable.
                Formats acceptés : PDF, JPG, JPEG et PNG.
                Taille maximale : 5 Mo par fichier.
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Autorisation ou justificatif officiel de la pharmacie *
                </label>
                <input type="file"
                       name="authorization_document"
                       class="form-control"
                       accept=".pdf,.jpg,.jpeg,.png" required>
            </div>

            <div class="mb-4">
                <label class="form-label">
                    Justificatif professionnel du responsable *
                </label>
                <input type="file"
                       name="order_document"
                       class="form-control"
                       accept=".pdf,.jpg,.jpeg,.png" required>
            </div>

            <div class="alert alert-warning">
                <i class="bi bi-info-circle me-2"></i>
                Votre compte restera désactivé jusqu'à l'approbation
                de votre demande par l'administrateur.
            </div>

            <button type="submit" class="btn btn-main w-100 py-3 fw-bold">
                Envoyer ma demande
                <i class="bi bi-arrow-right ms-2"></i>
            </button>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}">Retour à la connexion</a>
            </div>

        </form>
    </div>
</div>

</body>
</html>
