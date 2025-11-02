# Configuration Hugging Face pour Chatbot Médical - Pratiques 2025

## 🎯 Objectif

Utiliser **uniquement Hugging Face** comme API principale pour le chatbot médical, en respectant les pratiques Hugging Face 2025 pour l'API Inference publique et gratuite.

## ✅ Configuration requise

### 1. Obtenir un token Hugging Face

1. Créez un compte sur https://huggingface.co/
2. Allez sur https://huggingface.co/settings/tokens
3. Créez un nouveau token avec les permissions **"Read"**
4. Copiez le token (il commence par `hf_`)

### 2. Configurer dans `.env`

```env
# Hugging Face - API principale (OBLIGATOIRE)
HUGGINGFACE_API_KEY=hf_votre_token_ici
HUGGINGFACE_MODEL=google/flan-t5-large

# Google Gemini - Fallback optionnel (si Hugging Face échoue)
GOOGLE_API_KEY=votre_cle_google_ici
GOOGLE_MODEL=gemini-pro
```

### 3. Rafraîchir la configuration

```bash
php artisan config:clear
```

## 📋 Modèles compatibles API Inference 2025

Selon les pratiques Hugging Face 2025, tous les modèles ne sont pas disponibles via l'API Inference publique gratuite. Voici les modèles **testés et compatibles** :

### Modèles conversationnels (recommandés pour chatbot)

| Modèle | Description | Statut |
|--------|-------------|--------|
| `google/flan-t5-large` | Modèle conversationnel Google, optimisé pour tâches NLP | ✅ Compatible |
| `facebook/blenderbot-400M-distill` | Chatbot conversationnel Facebook (400M) | ✅ Compatible |
| `microsoft/DialoGPT-medium` | Modèle conversationnel Microsoft (medium) | ✅ Compatible |

### Modèles de génération de texte

| Modèle | Description | Statut |
|--------|-------------|--------|
| `gpt2` | GPT2 simple, toujours disponible | ✅ Compatible |
| `distilgpt2` | GPT2 léger et rapide | ✅ Compatible |

### Modèles instruct (peuvent nécessiter "inference enabled")

| Modèle | Description | Statut |
|--------|-------------|--------|
| `tiiuae/falcon-7b-instruct` | Modèle instruct généraliste | ⚠️ Peut nécessiter inference enabled |

## 🔄 Ordre de priorité du système

Le système fonctionne dans cet ordre :

1. **Hugging Face** (PRIORITÉ) - Essaie plusieurs modèles jusqu'à ce qu'un fonctionne
2. **Google Gemini** (FALLBACK) - Utilisé uniquement si Hugging Face échoue
3. **Fallback intelligent** - Réponses contextuelles si toutes les APIs échouent

## 🚀 Utilisation

### Test rapide

```bash
# Vérifier que le token est valide
curl -X GET https://huggingface.co/api/whoami \
  -H "Authorization: Bearer hf_VOTRE_TOKEN"
```

### Test d'un modèle

```php
use Illuminate\Support\Facades\Http;

$model = 'google/flan-t5-large';
$apiKey = env('HUGGINGFACE_API_KEY');

$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $apiKey,
    'Content-Type' => 'application/json'
])->post("https://api-inference.huggingface.co/models/$model", [
    'inputs' => "Question médicale : J'ai mal à la tête, que faire ?",
    'parameters' => [
        'max_new_tokens' => 200,
        'temperature' => 0.7
    ]
]);

if ($response->successful()) {
    $result = $response->json();
    echo "✅ Modèle fonctionne!\n";
} else {
    echo "❌ Erreur: " . $response->status() . "\n";
}
```

## 📝 Variables d'environnement

### Variables Hugging Face

| Variable | Description | Exemple |
|----------|-------------|---------|
| `HUGGINGFACE_API_KEY` | Token Hugging Face (obligatoire) | `hf_abc123...` |
| `HUGGINGFACE_MODEL` | Modèle à utiliser par défaut | `google/flan-t5-large` |

### Variables Fallback (optionnel)

| Variable | Description | Exemple |
|----------|-------------|---------|
| `GOOGLE_API_KEY` | Clé API Google Gemini (optionnel) | `AIza...` |
| `GOOGLE_MODEL` | Modèle Gemini à utiliser | `gemini-pro` |

## ⚠️ Bonnes pratiques 2025

1. **Token valide** : Assurez-vous que votre token Hugging Face est valide et a les permissions "Read"
2. **Modèles compatibles** : Utilisez uniquement les modèles compatibles avec l'API Inference publique
3. **Gestion d'erreurs** : Le système essaie automatiquement plusieurs modèles si le premier échoue
4. **Fallback** : Configurez Google Gemini comme fallback pour assurer la disponibilité du service

## 🔍 Dépannage

### Erreur 401 (Unauthorized)

**Problème** : Token invalide ou sans permissions

**Solution** :
1. Vérifiez que votre token commence par `hf_`
2. Régénérez un nouveau token sur https://huggingface.co/settings/tokens
3. Assurez-vous que le token a les permissions "Read"

### Erreur 404 (Model not found)

**Problème** : Le modèle n'est pas disponible via l'API Inference publique

**Solution** :
1. Le système essaie automatiquement d'autres modèles compatibles
2. Vérifiez la liste des modèles compatibles ci-dessus
3. Utilisez `google/flan-t5-large` qui est généralement toujours disponible

### Tous les modèles retournent 404

**Problème** : Aucun modèle n'est accessible

**Solutions** :
1. Vérifiez que votre token est valide
2. Le système utilisera automatiquement Google Gemini si configuré
3. Sinon, le fallback intelligent prendra le relais

## 📊 Vérification des logs

Pour voir quelle API est utilisée :

```bash
tail -f storage/logs/laravel.log | grep "Tentative avec l'API"
```

Pour voir les succès :

```bash
tail -f storage/logs/laravel.log | grep "Réponse générée avec succès"
```

## 🔗 Ressources

- [Hugging Face Tokens](https://huggingface.co/settings/tokens)
- [Hugging Face Inference API Documentation](https://huggingface.co/docs/api-inference/index)
- [Hugging Face Models](https://huggingface.co/models)
- [Google AI Studio (Gemini)](https://aistudio.google.com/)

## 📌 Notes importantes

1. **Hugging Face est la priorité** : Le système essaie d'abord Hugging Face avant toute autre API
2. **Plusieurs modèles** : Le système essaie automatiquement plusieurs modèles Hugging Face jusqu'à trouver un qui fonctionne
3. **Fallback automatique** : Si Hugging Face échoue, Google Gemini est utilisé automatiquement (si configuré)
4. **Respect des pratiques 2025** : Seuls les modèles compatibles avec l'API Inference publique sont utilisés




