<?php
// Script de vérification du token Hugging Face
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$token = config('services.huggingface.api_key');

if (empty($token)) {
    echo "❌ ERREUR: Token non trouvé dans la configuration\n";
    echo "Vérifiez que HUGGINGFACE_API_KEY est dans votre .env\n";
    exit(1);
}

echo "=== Vérification du Token Hugging Face ===\n\n";
echo "Token trouvé: " . substr($token, 0, 15) . "...\n";
echo "Longueur: " . strlen($token) . " caractères\n";

// Vérifications
$errors = [];

if (!str_starts_with($token, 'hf_')) {
    $errors[] = "❌ Le token ne commence pas par 'hf_'";
} else {
    echo "✅ Format correct (commence par hf_)\n";
}

if (strlen($token) < 20) {
    $errors[] = "❌ Le token semble trop court (devrait faire ~37 caractères)";
}

if (strpos($token, ' ') !== false) {
    $errors[] = "❌ Le token contient des espaces (supprimez-les !)";
}

if (!empty($errors)) {
    echo "\n❌ ERREURS DÉTECTÉES:\n";
    foreach ($errors as $error) {
        echo "   {$error}\n";
    }
    echo "\n→ Corrigez votre fichier .env\n";
    exit(1);
}

echo "\nTest d'authentification avec Hugging Face...\n";

$response = \Illuminate\Support\Facades\Http::withHeaders([
    'Authorization' => 'Bearer ' . $token
])->timeout(10)->get('https://huggingface.co/api/whoami');

$status = $response->status();

if ($status === 200) {
    $data = $response->json();
    echo "✅ TOKEN VALIDE !\n";
    echo "   Utilisateur: " . ($data['name'] ?? 'N/A') . "\n";
    echo "   Email: " . ($data['email'] ?? 'N/A') . "\n";
    echo "\n✅ Votre token fonctionne ! Le chatbot devrait fonctionner.\n";
} else {
    echo "❌ TOKEN INVALIDE (Status: {$status})\n";
    echo "   Réponse: " . $response->body() . "\n\n";
    echo "→ Votre token est invalide ou expiré.\n";
    echo "→ Créez un NOUVEAU token sur: https://huggingface.co/settings/tokens\n";
    echo "→ Suivez les instructions dans GUIDE_CREATION_TOKEN_HF.md\n";
    exit(1);
}




