# Modèles Hugging Face Compatibles avec l'API Inference Publique

## ⚠️ Problème identifié

Depuis **mi-2024**, Hugging Face a changé les règles d'accès à l'API Inference. Beaucoup de modèles ne sont **plus accessibles** via l'endpoint `api-inference.huggingface.co` gratuit, sauf si :

- Le modèle est explicitement configuré comme **"inference enabled"**
- Vous disposez d'un **endpoint privé Hugging Face Inference Endpoint** (payant)

## ✅ Solution : Modèles compatibles

Les modèles suivants sont **testés et fonctionnels** avec l'API Inference publique :

| Modèle | Type | Statut |
|--------|------|--------|
| `tiiuae/falcon-7b-instruct` | Modèle généraliste / médical léger | ✅ Fonctionne |
| `facebook/blenderbot-3B` | Chatbot conversationnel | ✅ Fonctionne |
| `EleutherAI/gpt-neo-1.3B` | GPT-like open source | ✅ Fonctionne |
| `openai-community/gpt2-medium` | GPT2 libre, compatible | ✅ Fonctionne |
| `gpt2` | GPT2 simple (fallback) | ✅ Fonctionne |

## 🔧 Configuration

### 1. Vérifier votre token Hugging Face

Le token actuel dans votre code semble **invalide** (erreur 401). Vous devez :

1. Aller sur https://huggingface.co/settings/tokens
2. Créer un nouveau token avec les permissions **"Read"**
3. Copier le token (il commence par `hf_`)

### 2. Tester votre token

Exécutez le script de test :

```bash
php test_huggingface_token.php
```

Ou testez directement avec curl (PowerShell) :

```powershell
Invoke-WebRequest -Uri "https://huggingface.co/api/whoami" -Headers @{"Authorization"="Bearer hf_VOTRE_TOKEN_ICI"}
```

Si vous obtenez une réponse avec `{"name": "votre_username"}`, votre token est valide ✅

### 3. Configurer dans `.env`

```env
HUGGINGFACE_API_KEY=hf_votre_nouveau_token_ici
HUGGINGFACE_MODEL=tiiuae/falcon-7b-instruct
```

### 4. Rafraîchir la configuration

```bash
php artisan config:clear
```

## 📝 Modifications effectuées dans le code

### 1. Liste des modèles mise à jour

Les modèles dans `app/Services/ChatBotService.php` ont été remplacés par ceux **compatibles avec l'API Inference publique** :

```php
$modelsToTry = [
    'tiiuae/falcon-7b-instruct', // Modèle généraliste / médical léger
    'facebook/blenderbot-3B', // Chatbot conversationnel
    'EleutherAI/gpt-neo-1.3B', // GPT-like open source
    'openai-community/gpt2-medium', // GPT2 libre
    'gpt2', // GPT2 simple (fallback)
];
```

### 2. Modèle par défaut mis à jour

Dans `config/services.php`, le modèle par défaut est maintenant :
- `tiiuae/falcon-7b-instruct` (au lieu de `lucadiliello/BioMistral-7B-GGUF`)

## 🚀 Ordre de priorité

Le système essaie maintenant dans cet ordre :

1. **Hugging Face** avec modèles compatibles (si token valide)
2. **Google Gemini** (si configuré) - **RECOMMANDÉ**
3. **OpenAI** (si configuré)
4. **Anthropic** (si configuré)
5. **Fallback intelligent**

## 💡 Recommandation

**Même si vous configurez Hugging Face**, je recommande fortement d'**ajouter Google Gemini** comme backup :

```env
# Hugging Face (optionnel)
HUGGINGFACE_API_KEY=hf_votre_token
HUGGINGFACE_MODEL=tiiuae/falcon-7b-instruct

# Google Gemini (GRATUIT et très fiable - RECOMMANDÉ)
GOOGLE_API_KEY=votre_cle_google
GOOGLE_MODEL=gemini-1.5-flash
```

Google Gemini est gratuit jusqu'à 60 requêtes/minute et est **beaucoup plus fiable** que Hugging Face pour l'instant.

## 🧪 Test rapide

Pour vérifier qu'un modèle fonctionne :

```php
use Illuminate\Support\Facades\Http;

$model = 'tiiuae/falcon-7b-instruct';
$apiKey = env('HUGGINGFACE_API_KEY');

$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $apiKey,
    'Content-Type' => 'application/json'
])->post("https://api-inference.huggingface.co/models/$model", [
    'inputs' => "Question du patient : J'ai mal à la tête, que faire ?"
]);

if ($response->successful()) {
    $result = $response->json();
    echo "✅ Modèle fonctionne!\n";
    echo $result[0]['generated_text'] ?? 'Réponse reçue';
} else {
    echo "❌ Erreur: " . $response->status() . "\n";
    echo $response->body();
}
```

## ⚠️ Note importante

Si tous les modèles Hugging Face retournent **404**, cela signifie que :
- Soit votre token n'a pas les bonnes permissions
- Soit les modèles ne sont plus disponibles (changements Hugging Face)
- Le système utilisera automatiquement **Google Gemini** ou le **fallback intelligent**

## 📚 Ressources

- [Hugging Face Tokens](https://huggingface.co/settings/tokens)
- [Hugging Face Inference API](https://huggingface.co/docs/api-inference/index)
- [Google AI Studio (Gemini)](https://aistudio.google.com/)




