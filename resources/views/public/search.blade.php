<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PharmaFind - Trouver une pharmacie</title>
    <style>
        * { box-sizing: border-box; }
        body { background:#000; color:#fff; font-family: Arial, sans-serif; margin:0; padding:20px; }
        .card { max-width:620px; margin:auto; border:1px solid #333; border-radius:16px; padding:16px; }
        .header { background:#20b2b8; border-radius:10px; text-align:center; padding:18px; margin-bottom:18px; }
        .header h1 { margin:0 0 8px; font-size:24px; }
        .titre { display:block; color:#6b7aa8; margin:14px 0 6px; }
        input[type=text], select {
            width:100%; padding:14px 20px; border-radius:30px; background:#000; color:#fff;
            border:1px solid #333; font-size:16px; margin-bottom:12px;
        }
        .radios label { margin-right:22px; font-size:17px; cursor:pointer; }
        button { width:100%; padding:14px; border:none; border-radius:30px; background:#20b2b8;
                 color:#fff; font-weight:bold; font-size:16px; cursor:pointer; margin-top:14px; }
        .result { border:1px solid #333; border-radius:12px; padding:12px 16px; margin-top:12px; }
        .result h3 { margin:0 0 6px; }
        .result small { color:#aaa; display:block; margin-bottom:4px; }
        .badge { display:inline-block; background:#123b3d; color:#5fe0d0; border-radius:20px;
                 padding:3px 10px; font-size:13px; margin:2px 4px 6px 0; }
        .maps { color:#20b2b8; text-decoration:none; font-weight:bold; }
        .pied { text-align:center; margin-top:18px; font-size:14px; }
        .pied a { color:#6b7aa8; }
    </style>
</head>
<body>
<div class="card">

    <div class="header">
        <h1>📍 PharmaFind</h1>
        <div>Trouver la pharmacie la plus proche de chez vous !</div>
    </div>

    <form method="GET" action="{{ route('home') }}">

        <label class="titre">Médicament recherché (facultatif)</label>
        <input type="text" name="q" id="q" list="suggestions" autocomplete="off"
               placeholder="Ex : Doliprane" value="{{ request('q') }}">
        <datalist id="suggestions"></datalist>

        <label class="titre">Choisissez votre localisation</label>
        <select name="region" id="region">
            <option value="">- Gouvernorat -</option>
            @foreach ($regions as $r)
                <option value="{{ $r }}" @selected(request('region') === $r)>{{ $r }}</option>
            @endforeach
        </select>

        <select name="city" id="city" data-selected="{{ request('city') }}">
            <option value="">- Délégation / Ville -</option>
        </select>

        <label class="titre">Type de pharmacie</label>
        <div class="radios">
            <label><input type="radio" name="type" value="jour" @checked(request('type', 'jour') === 'jour')> Jour</label>
            <label><input type="radio" name="type" value="nuit" @checked(request('type') === 'nuit')> Nuit</label>
        </div>

        <button type="submit" name="rechercher" value="1">RECHERCHER UNE PHARMACIE</button>
    </form>

    @if (!is_null($pharmacies))
        <h2 style="margin-top:26px;">{{ $pharmacies->count() }} pharmacie(s) trouvée(s)</h2>

        @forelse ($pharmacies as $p)
            <div class="result">
                <h3>{{ $p->name }}</h3>

                <small>
                    {{ $p->address }} · {{ $p->city }}
                    @if ($p->region && $p->region !== $p->city), {{ $p->region }} @endif
                </small>

                @if ($p->phone)
                    <small>📞 {{ $p->phone }}</small>
                @endif

                @if ($p->relationLoaded('stocks'))
                    @foreach ($p->stocks->take(3) as $stock)
                        <span class="badge">✔ {{ $stock->medicament->name }} · {{ $stock->declared_quantity }} en stock</span>
                    @endforeach
                @endif

                <div>
                    <a class="maps" href="{{ $p->maps_url }}" target="_blank" rel="noopener">
                        📍 Voir dans Google Maps
                    </a>
                </div>
            </div>
        @empty
            <p>Aucune pharmacie ne correspond à votre recherche.</p>
        @endforelse
    @endif

    <div class="pied">
        <a href="{{ route('login') }}">Espace pharmacien</a>
    </div>
</div>

<script>
    const selRegion = document.getElementById('region');
    const selCity   = document.getElementById('city');
    const inputQ    = document.getElementById('q');
    const datalist  = document.getElementById('suggestions');

    // Délégations / villes du gouvernorat choisi
    async function chargerVilles() {
        selCity.innerHTML = '<option value="">- Délégation / Ville -</option>';
        if (!selRegion.value) return;

        const res = await fetch("{{ route('api.villes') }}?region=" + encodeURIComponent(selRegion.value));
        const villes = await res.json();

        villes.forEach(v => {
            const opt = new Option(v, v);
            if (v === selCity.dataset.selected) opt.selected = true;
            selCity.add(opt);
        });
    }

    selRegion.addEventListener('change', () => { selCity.dataset.selected = ''; chargerVilles(); });
    chargerVilles();

    // Suggestions de médicaments pendant la saisie
    let minuterie;
    inputQ.addEventListener('input', () => {
        clearTimeout(minuterie);
        minuterie = setTimeout(async () => {
            if (inputQ.value.trim().length < 2) { datalist.innerHTML = ''; return; }
            const res = await fetch("{{ route('api.medicaments') }}?q=" + encodeURIComponent(inputQ.value.trim()));
            const noms = await res.json();
            datalist.innerHTML = noms.map(n => `<option value="${n.replace(/"/g, '&quot;')}">`).join('');
        }, 250);
    });
</script>
</body>
</html>