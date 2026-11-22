
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil - PharmaFind</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7f6;
            padding: 30px;
        }

        .container {
            max-width: 650px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        h1 {
            color: #167d63;
        }

        label {
            display: block;
            margin-top: 18px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background: #167d63;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            margin-top: 25px;
            cursor: pointer;
        }

        a {
            color: #167d63;
            text-decoration: none;
            margin-left: 15px;
        }

        .success {
            background: #d1fae5;
            color: #065f46;
            padding: 12px;
            margin-bottom: 15px;
        }

        .error {
            color: #dc2626;
            margin-top: 6px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Mon profil pharmacien</h1>

    <p>Gérez les informations de votre pharmacie.</p>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('pharmacien.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <label for="name">Nom de la pharmacie</label>
        <input
            type="text"
            name="name"
            id="name"
            value="{{ old('name', $pharmacy->name) }}"
            required
        >
        @error('name')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="address">Adresse</label>
        <input
            type="text"
            name="address"
            id="address"
            value="{{ old('address', $pharmacy->address) }}"
            required
        >
        @error('address')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="city">Ville</label>
        <input
            type="text"
            name="city"
            id="city"
            value="{{ old('city', $pharmacy->city) }}"
            required
        >
        @error('city')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="region">Région</label>
        <input
            type="text"
            name="region"
            id="region"
            value="{{ old('region', $pharmacy->region) }}"
        >
        @error('region')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="phone">Téléphone</label>
        <input
            type="text"
            name="phone"
            id="phone"
            value="{{ old('phone', $pharmacy->phone) }}"
        >
        @error('phone')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Enregistrer les modifications</button>

        <a href="{{ route('pharmacien.medicaments.index') }}">
            Retour au stock
        </a>
    </form>

</div>

</body>
</html>
