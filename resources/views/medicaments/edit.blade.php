
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le stock - PharmaFind</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7f6;
            padding: 30px;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        h1 {
            color: #167d63;
        }

        .medicine-name {
            background: #e5f2ed;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        label {
            display: block;
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

        .error {
            color: #dc2626;
            margin-top: 8px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Modifier le stock</h1>

    <div class="medicine-name">
        <strong>Médicament :</strong> {{ $medicament->name }}

        @if($medicament->dosage)
            - {{ $medicament->dosage }}
        @endif

        @if($medicament->form)
            ({{ $medicament->form }})
        @endif
    </div>

    <form action="{{ route('pharmacien.medicaments.update', $medicament) }}"
          method="POST">

        @csrf
        @method('PUT')

        <label for="declared_quantity">
            Nouvelle quantité disponible
        </label>

        <input
            type="number"
            name="declared_quantity"
            id="declared_quantity"
            min="0"
            value="{{ old('declared_quantity', $stock->declared_quantity) }}"
            required
        >

        @error('declared_quantity')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Enregistrer les modifications</button>

        <a href="{{ route('pharmacien.medicaments.index') }}">
            Annuler
        </a>

    </form>

</div>

</body>
</html>
