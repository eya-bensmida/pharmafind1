<?php

namespace App\Services;

class LocationNormalizer
{
    private array $governorates = [
        'Ariana' => ['ariana', 'أريانة', 'gouvernorat ariana'],
        'Béja' => ['beja', 'béja', 'باجة', 'gouvernorat de beja'],
        'Ben Arous' => ['ben arous', 'بن عروس'],
        'Bizerte' => ['bizerte', 'بنزرت'],
        'Gabès' => ['gabes', 'gabès', 'قابس'],
        'Gafsa' => ['gafsa', 'قفصة'],
        'Jendouba' => ['jendouba', 'جندوبة'],
        'Kairouan' => ['kairouan', 'القيروان'],
        'Kasserine' => ['kasserine', 'القصرين'],
        'Kébili' => ['kebili', 'kébili', 'قبلي'],
        'Manouba' => ['manouba', 'la manouba', 'منوبة'],
        'Kef' => ['kef', 'le kef', 'الكاف'],
        'Mahdia' => ['mahdia', 'المهدية'],
        'Médenine' => ['medenine', 'médenine', 'مدنين'],
        'Monastir' => ['monastir', 'المنستير'],
        'Nabeul' => ['nabeul', 'نابل'],
        'Sfax' => ['sfax', 'صفاقس'],
        'Sidi Bouzid' => ['sidi bouzid', 'سيدي بوزيد'],
        'Siliana' => ['siliana', 'سليانة'],
        'Sousse' => [
            'sousse',
            'سوسة',
            'gouvernorat de sousse',
            'gouvernorat sousse',
        ],
        'Tataouine' => ['tataouine', 'تطاوين'],
        'Tozeur' => ['tozeur', 'توزر'],
        'Tunis' => ['tunis', 'تونس', 'gouvernorat de tunis'],
        'Zaghouan' => ['zaghouan', 'زغوان'],
    ];

    private array $localities = [

        'Ariana' => [
            'أريانة' => 'Ariana',
            'التضامن' => 'Ettadhamen',
            'قلعة الأندلس' => 'Kalaat El Andalous',
        ],

        'Béja' => [
            'مجاز الباب' => 'Medjez el-Bab',
            'وادي الزرقاء' => 'Oued Zarga',
        ],

        'Ben Arous' => [
            'الزهراء' => 'Ezzahra',
            'المدينة الجديدة' => 'Nouvelle Médina',
            'رادس' => 'Radès',
        ],

        'Bizerte' => [
            'بنزرت' => 'Bizerte',
            'الزواوين' => 'Zouaouine',
            'غزالة' => 'Ghazala',
        ],

        'Gafsa' => [
            'السند' => 'Sened',
            'قفصة' => 'Gafsa',
        ],

        'Jendouba' => [
            'حمام بورقيبة' => 'Hammam Bourguiba',
            'غار الدماء' => 'Ghardimaou',
        ],

        'Kébili' => [
            'الفوار' => 'El Faouar',
            'دوز' => 'Douz',
        ],

        'Mahdia' => [
            'المهدية' => 'Mahdia',
        ],

        'Manouba' => [
            'الجديدة' => 'Nouvelle Médina',
            'المرناقية' => 'Mornaguia',
        ],

        'Médenine' => [
            'بنقردان' => 'Ben Gardane',
            'مدنين' => 'Medenine',
        ],

        'Monastir' => [
            'المنستير' => 'Monastir',
            'قصر هلال' => 'Ksar Hellal',
            'طبلبة' => 'Teboulba',
        ],

        'Nabeul' => [
            'الحمامات' => 'Hammamet',
            'دار شعبان الفهري' => 'Dar Chaâbane El Fehri',
            'سليمان' => 'Soliman',
            'قربة' => 'Korba',
            'قليبية' => 'Kelibia',
            'نابل' => 'Nabeul',
        ],

        'Sfax' => [
            'صفاقس' => 'Sfax',
            'طينة' => 'Thyna',
            'عقارب' => 'Agareb',
            'ساقية الزيت' => 'Sakiet Ezzit',
            'العوابد' => 'El Aouabed',
        ],

        'Sousse' => [
            'النفيضة' => 'Enfidha',
            'سوسة' => 'Sousse',
        ],

        'Tataouine' => [
            'تطاوين' => 'Tataouine',
            'غمراسن' => 'Ghomrassen',
        ],

        'Tunis' => [
            'تونس' => 'Tunis',
            'المرسى' => 'La Marsa',
            'سيدي حسين' => 'Sidi Hassine',
            'العوينة' => 'El Aouina',
        ],
    ];

    /**
     * Normalisation générale des écritures.
     */
    public function normalize(string $value): string
    {
        $value = trim(mb_strtolower($value, 'UTF-8'));

        // Uniformiser les caractères arabes
        $value = strtr($value, [
            'أ' => 'ا',
            'إ' => 'ا',
            'آ' => 'ا',
            'ى' => 'ي',
            'ـ' => '',
        ]);

        // Supprimer les signes diacritiques arabes
        $value = preg_replace('/[\x{064B}-\x{065F}\x{0670}]/u', '', $value);

        // Uniformiser quelques accents latins
        $value = strtr($value, [
            'é' => 'e', 'è' => 'e', 'ê' => 'e',
            'ë' => 'e', 'à' => 'a', 'â' => 'a',
            'î' => 'i', 'ï' => 'i', 'ô' => 'o',
            'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'ç' => 'c',
        ]);

        // Uniformiser les séparateurs
        $value = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $value);

        return trim(preg_replace('/\s+/u', ' ', $value));
    }

    /**
     * Reconnaître un gouvernorat.
     */
    public function normalizeGovernorate(string $value): ?string
    {
        $normalized = $this->normalize($value);

        foreach ($this->governorates as $canonical => $variants) {
            if ($normalized === $this->normalize($canonical)) {
                return $canonical;
            }

            foreach ($variants as $variant) {
                if ($normalized === $this->normalize($variant)) {
                    return $canonical;
                }
            }
        }

        return null;
    }

    /**
     * Reconnaître une localité dans son gouvernorat.
     */
    public function normalizeLocality(
        string $value,
        string $governorate
    ): ?string {
        $canonicalGovernorate = $this->normalizeGovernorate($governorate);

        if ($canonicalGovernorate === null) {
            return null;
        }

        $normalizedValue = $this->normalize($value);

        // Comparer avec les correspondances arabes
        foreach ($this->localities[$canonicalGovernorate] ?? [] as $variant => $canonical) {
            if ($normalizedValue === $this->normalize($variant)) {
                return $canonical;
            }
        }

        // Conserver les noms français inconnus
        return trim($value);
    }
    /**
 * Reconnaître un gouvernorat dans une phrase complète.
 */
public function findGovernorateInText(string $text): ?string
{
    $normalizedText = ' ' . $this->normalize($text) . ' ';

    foreach ($this->governorates as $canonical => $variants) {

        $names = array_merge([$canonical], $variants);

        foreach ($names as $name) {

            $normalizedName = $this->normalize($name);

            if ($normalizedName === '') {
                continue;
            }

            if (str_contains(
                $normalizedText,
                ' ' . $normalizedName . ' '
            )) {
                return $canonical;
            }
        }
    }

    return null;
}
}
