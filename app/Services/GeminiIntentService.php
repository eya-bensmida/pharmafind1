<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiIntentService
{
    public function ask(string $prompt): string
    {
        $apiKey = config('services.gemini.key');
        $model = config('services.gemini.model', 'gemini-flash-lite-latest');

        if (!$apiKey) {
            return "Le service Gemini n'est pas encore configuré. Vérifiez la clé API.";
        }

        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

            $response = Http::connectTimeout(10)
                ->timeout(30)
                ->withHeaders([
                    'x-goog-api-key' => $apiKey,
                ])
                ->post($url, [
                    'systemInstruction' => [
                        'parts' => [[
                            'text' => "Tu es PharmaFind Assistant.
Réponds dans la langue de l'utilisateur : français, arabe ou tunisien.
Sois clair, poli et concis.
Ne prétends jamais qu'un médicament est disponible sans résultats réels de PharmaFind.
Ne donne aucun diagnostic médical et ne recommande jamais de remplacer un médicament prescrit."
                        ]]
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [['text' => $prompt]]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.2,
                        'maxOutputTokens' => 700,
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('Gemini API error', [
                    'status' => $response->status(),
                    'error' => $response->json('error.message'),
                ]);

                return "Je rencontre un problème temporaire avec l'assistant. Réessayez dans quelques instants.";
            }

            return $response->json('candidates.0.content.parts.0.text')
                ?? "Je n'ai pas pu générer une réponse. Pouvez-vous reformuler ?";
        } catch (\Throwable $e) {
            Log::error('Gemini connection error: ' . $e->getMessage());

            return "Impossible de contacter Gemini pour le moment. Vérifiez votre connexion.";
        }
    }

    public function extractMedicineRequest(string $message): array
    {
        $prompt = <<<PROMPT
Tu es un système de reconnaissance des demandes de recherche de médicaments pour PharmaFind.

Analyse le message et retourne UNIQUEMENT un objet JSON valide, sans Markdown ni explication.

Règles :
- Reconnais les demandes de médicaments même si elles sont formulées sous forme de phrase ou de question.
- Reconnais le français, l'arabe et le tunisien.
- medicine_name : nom exact du médicament mentionné, ou null.
- city : ville mentionnée, ou null.
- is_medicine_search : true si l'utilisateur cherche un médicament ou sa disponibilité, sinon false.
- Ne considère pas une simple salutation ou une question générale comme une recherche de médicament.
- N'invente jamais un nom de médicament ou une ville.

Exemples :
Message : "Doliprane"
JSON : {"medicine_name":"Doliprane","city":null,"is_medicine_search":true}

Message : "Je cherche du Doliprane à Tunis"
JSON : {"medicine_name":"Doliprane","city":"Tunis","is_medicine_search":true}

Message : "Est-ce que vous avez de l'Augmentin ?"
JSON : {"medicine_name":"Augmentin","city":null,"is_medicine_search":true}

Message : "نحب نلقى Doliprane في تونس"
JSON : {"medicine_name":"Doliprane","city":"Tunis","is_medicine_search":true}

Message : "Bonjour, comment ça va ?"
JSON : {"medicine_name":null,"city":null,"is_medicine_search":false}

Message utilisateur :
{$message}
PROMPT;

        $answer = $this->ask($prompt);

        $answer = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($answer));

        $data = json_decode($answer, true);

        if (!is_array($data)) {
            Log::warning('Gemini intent JSON invalid', [
                'response' => mb_substr($answer, 0, 500),
            ]);

            return [
                'medicine_name' => null,
                'city' => null,
                'is_medicine_search' => false,
            ];
        }

        return [
            'medicine_name' => isset($data['medicine_name']) && is_string($data['medicine_name'])
                ? trim($data['medicine_name'])
                : null,
            'city' => isset($data['city']) && is_string($data['city'])
                ? trim($data['city'])
                : null,
            'is_medicine_search' => ($data['is_medicine_search'] ?? false) === true,
        ];
    }
}
