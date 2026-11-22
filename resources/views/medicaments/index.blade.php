
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mes médicaments - PharmaFind</title>

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background: #f4f7f6;
            padding: 25px;
            color: #263238;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 28px;
            border-radius: 12px;
        }

        h1 { color: #167d63; }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn {
            display: inline-block;
            background: #167d63;
            color: white;
            padding: 12px 18px;
            border-radius: 7px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .search {
            display: flex;
            gap: 10px;
            margin: 25px 0;
        }

        .search input {
            flex: 1;
            min-width: 0;
            padding: 13px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 13px 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #eaf5f1;
            color: #16634f;
            position: sticky;
            top: 0;
        }

        .quantity {
            font-weight: bold;
            color: #167d63;
        }

        .message {
            padding: 13px;
            margin: 15px 0;
            border-radius: 7px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .empty {
            padding: 30px;
            text-align: center;
            color: #64748b;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .edit {
            color: #167d63;
            text-decoration: none;
        }

        .delete {
            color: #dc2626;
            background: none;
            border: none;
            cursor: pointer;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        @media(max-width: 750px) {
            body { padding: 10px; }
            .container { padding: 15px; }
            table { min-width: 850px; }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="top">
        <div>
            <h1>Mes médicaments</h1>
            <p>Gestion du catalogue et du stock de votre pharmacie.</p>
        </div>

        <a href="{{ route('pharmacien.medicaments.create') }}" class="btn">
            + Ajouter un nouveau médicament
        </a>
    </div>

    @if(session('success'))
        <div class="message success">
            {{ session('success') }}
        </div>
    @endif

    <form
        action="{{ route('pharmacien.medicaments.index') }}"
        method="GET"
        class="search"
    >

        <input
            type="search"
            name="q"
            value="{{ $search }}"
            placeholder="Rechercher par nom, principe actif, catégorie ou laboratoire..."
        >

        <button class="btn" type="submit">
            Rechercher
        </button>

        @if($search)
            <a href="{{ route('pharmacien.medicaments.index') }}" class="btn">
                Réinitialiser
            </a>
        @endif

    </form>

    <p>
        <strong>{{ $total }}</strong>
        médicament(s) trouvé(s).
    </p>

    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Principe actif</th>
                    <th>Dosage</th>
                    <th>Forme</th>
                    <th>Catégorie</th>
                    <th>Laboratoire</th>
                    <th>Quantité</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($medicaments as $medicament)

                    <tr>

                        <td>
                            <strong>{{ $medicament->name }}</strong>
                        </td>

                        <td>{{ $medicament->active_ingredient ?? '-' }}</td>

                        <td>{{ $medicament->dosage ?? '-' }}</td>

                        <td>{{ $medicament->form ?? '-' }}</td>

                        <td>{{ $medicament->category ?? '-' }}</td>

                        <td>{{ $medicament->laboratory ?? '-' }}</td>

                        <td class="quantity">
                            {{ $medicament->stocks->first()->declared_quantity ?? 0 }}
                        </td>

                        <td>
                            <div class="actions">

                                <a
                                    class="edit"
                                    href="{{ route('pharmacien.medicaments.edit', $medicament) }}"
                                >
                                    Modifier
                                </a>

                                <form
                                    action="{{ route('pharmacien.medicaments.destroy', $medicament) }}"
                                    method="POST"
                                    onsubmit="return confirm('Retirer ce médicament de votre stock ?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button class="delete" type="submit">
                                        Retirer
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="empty">
                            Aucun médicament trouvé dans votre stock.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</body>
</html>
