<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ChatbotService
{
    public function __construct(
        private GeminiIntentService $gemini,
        private PharmacySearch $pharmacySearch,
        private LocationNormalizer $locationNormalizer
    ) {}

    /**
     * Traiter le message de l'utilisateur.
     */
    public function reply(string $message): string
    {
        try {

            // 1. Reconnaître le gouvernorat dans toute la phrase.
            $governorate = $this->locationNormalizer
                ->findGovernorateInText($message);

            // 2. Ajouter les informations géographiques
            // au message transmis à Gemini.
            $messageForGemini = $message;

            if ($governorate !== null) {
                $messageForGemini .=
                    "\nGouvernorat reconnu : {$governorate}";
            }

            // 3. Extraire l'intention et le médicament.
            $intent = $this->gemini
                ->extractMedicineRequest($messageForGemini);

            Log::info('PharmaFind - Analyse du chatbot', [
                'message' => $message,
                'governorate' => $governorate,
                'intent' => $intent,
            ]);

            // 4. Vérifier s'il s'agit d'une recherche
            // de médicament.
            if (
                ($intent['is_medicine_search'] ?? false) === true
                && !empty($intent['medicine_name'])
            ) {

                $medicineName = trim($intent['medicine_name']);

                // 5. Récupérer la ville extraite par Gemini.
                $city = $intent['city'] ?? null;

                if (is_string($city)) {
                    $city = trim($city);

                    if ($city === '') {
                        $city = null;
                    }
                } else {
                    $city = null;
                }

                // Ne pas confondre le gouvernorat avec la ville.
                if (
                    $city !== null
                    && $governorate !== null
                    && $this->locationNormalizer
                        ->normalizeGovernorate($city) === $governorate
                ) {
                    $city = null;
                }

                // 6. Enregistrer les paramètres de recherche.
                Log::info('PharmaFind - Paramètres de recherche', [
                    'medicine' => $medicineName,
                    'governorate' => $governorate,
                    'city' => $city,
                ]);

                // 7. Rechercher le médicament dans les pharmacies.
                // IMPORTANT : PharmacySearch doit accepter
                // trois paramètres :
                // médicament, gouvernorat et ville.
                $results = $this->pharmacySearch->search(
                    $medicineName,
                    $governorate,
                    $city
                );

                Log::info('PharmaFind - Résultats de recherche', [
                    'count' => count($results),
                ]);

                // 8. Aucun résultat trouvé.
                if (empty($results)) {

                    return $this->gemini->ask(
                        "La recherche du médicament {$medicineName}"
                        . ($governorate
                            ? " dans le gouvernorat de {$governorate}"
                            : "")
                        . ($city ? " dans la ville de {$city}" : "")
                        . " n'a retourné aucun résultat dans la base de données. "
                        . "Explique-le poliment à l'utilisateur. "
                        . "Ne prétends jamais que le médicament est disponible."
                    );
                }

                // 9. Préparer les résultats.
                $details = json_encode(
                    $results,
                    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
                );

                // 10. Générer une réponse à partir des résultats réels.
                return $this->gemini->ask(
                    "L'utilisateur recherche le médicament : {$medicineName}.\n"
                    . "Gouvernorat : "
                    . ($governorate ?? 'Non précisé')
                    . "\nVille : "
                    . ($city ?? 'Non précisée')
                    . "\n\nVoici les résultats réels de la base PharmaFind :\n"
                    . $details
                    . "\n\nPrésente clairement les pharmacies trouvées, "
                    . "leurs adresses et les informations disponibles. "
                    . "Utilise uniquement les informations fournies. "
                    . "N'invente aucune disponibilité ni aucune pharmacie."
                );
            }

            // 11. Question générale ou autre intention.
            return $this->gemini->ask($message);

        } catch (\Throwable $e) {

            // 12. Enregistrer les erreurs.
            Log::error('PharmaFind - Erreur chatbot', [
                'message' => $message,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return "Une erreur est survenue pendant le traitement "
                . "de votre demande. Veuillez réessayer.";
        }
    }
}
