# Configuration Hugging Face Unique - Chatbot Médical

## 🎯 Configuration

Le chatbot utilise **uniquement Hugging Face** avec **un seul modèle** configuré. Aucun basculement vers d'autres APIs.

## ✅ Configuration requise

### 1. Obtenir un token Hugging Face

1. Créez un compte sur https://huggingface.co/
2. Allez sur https://huggingface.co/settings/tokens
3. Créez un nouveau token avec les permissions **"Read"**
4. Copiez le token (il commence par `hf_`)

### 2. Configurer dans `.env`

```env
# Hugging Face - UNIQUE API pour le chatbot
HUGGINGFACE_API_KEY=hf_votre_token_ici
HUGGINGFACE_MODEL=google/flan-t5-large
```

**Modèle par défaut** : `google/flan-t5-large` (compatible API Inference 2025)

### 3. Rafraîchir la configuration

```bash
php artisan config:clear
```

## 📋 Modèle utilisé

Le système utilise **un seul modèle Hugging Face** configuré dans `.env` :

- **Modèle par défaut** : `google/flan-t5-large`
- **Type** : Modèle conversationnel compatible API Inference 2025
- **Statut** : ✅ Compatible avec l'API Inference publique

### Modèles alternatifs compatibles

Si vous souhaitez utiliser un autre modèle, vous pouvez configurer :

```env
# Options de modèles compatibles :
HUGGINGFACE_MODEL=google/flan-t5-large          # Modèle conversationnel (recommandé)
HUGGINGFACE_MODEL=facebook/blenderbot-400M-distill  # Chatbot conversationnel
HUGGINGFACE_MODEL=microsoft/DialoGPT-medium     # Modèle conversationnel Microsoft
HUGGINGFACE_MODEL=gpt2                         # GPT2 simple (toujours disponible)
```

## 🔧 Fonctionnement

### 1. Vérification de la configuration

Le système vérifie que `HUGGINGFACE_API_KEY` est configuré. Si absent, retourne une erreur claire.

### 2. Appel API unique

Le système appelle **uniquement** le modèle Hugging Face configuré via l'API Inference :

```
POST https://api-inference.huggingface.co/models/{MODEL}
Authorization: Bearer {HUGGINGFACE_API_KEY}
Content-Type: application/json

{
  "inputs": "{prompt}",
  "parameters": {
    "max_new_tokens": 400,
    "temperature": 0.8,
    "return_full_text": false,
    "repetition_penalty": 1.2,
    "do_sample": true,
    "top_p": 0.92
  }
}
```

### 3. Gestion des erreurs

Si l'API Hugging Face échoue, le système retourne une erreur claire **sans basculer** sur d'autres APIs :

- **401/403** : Erreur d'authentification → Vérifiez votre clé API
- **404** : Modèle non trouvé → Vérifiez que le modèle est disponible
- **503** : Modèle en chargement → Réessayez dans quelques instants
- **Autre** : Erreur API → Détails dans les logs

## 📝 Variables d'environnement

| Variable | Description | Exemple | Obligatoire |
|----------|-------------|---------|-------------|
| `HUGGINGFACE_API_KEY` | Token Hugging Face | `hf_abc123...` | ✅ Oui |
| `HUGGINGFACE_MODEL` | Modèle à utiliser | `google/flan-t5-large` | Non (défaut) |

## 🧪 Test de l'API

### Test du token

```bash
curl -X GET https://huggingface.co/api/whoami \
  -H "Authorization: Bearer hf_VOTRE_TOKEN"
```

Si vous obtenez `{"name": "votre_username"}`, votre token est valide ✅

### Test du modèle

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
    print_r($result);
} else {
    echo "❌ Erreur: " . $response->status() . "\n";
    echo $response->body();
}
```

## 📊 Vérification des logs

Pour voir les appels API :

```bash
tail -f storage/logs/laravel.log | grep "Hugging Face"
```

Pour voir les succès :

```bash
tail -f storage/logs/laravel.log | grep "Réponse générée avec succès"
```

## ⚠️ Bonnes pratiques

1. **Un seul modèle** : Configurez un modèle fiable et compatible dans `.env`
2. **Token valide** : Assurez-vous que votre token a les permissions "Read"
3. **Modèle compatible** : Utilisez uniquement des modèles compatibles avec l'API Inference publique 2025
4. **Gestion d'erreurs** : Les erreurs sont clairement loggées et retournées à l'utilisateur

## 🔍 Dépannage

### Erreur : "Configuration manquante"

**Solution** : Ajoutez `HUGGINGFACE_API_KEY` dans votre fichier `.env`

### Erreur : "Erreur d'authentification (401)"

**Solution** :
1. Vérifiez que votre token commence par `hf_`
2. Régénérez un nouveau token sur https://huggingface.co/settings/tokens
3. Vérifiez que le token a les permissions "Read"

### Erreur : "Modèle non trouvé (404)"

**Solution** :
1. Vérifiez que le modèle est bien disponible sur Hugging Face
2. Utilisez `google/flan-t5-large` qui est généralement toujours disponible
3. Vérifiez l'orthographe du nom du modèle dans `.env`

### Erreur : "Modèle temporairement indisponible (503)"

**Solution** : Le modèle est en cours de chargement. Attendez quelques instants et réessayez.

## 📌 Points importants

1. **Aucun basculement** : Le système n'utilise QUE Hugging Face
2. **Un seul modèle** : Le modèle configuré dans `.env` est utilisé
3. **Erreurs claires** : Les erreurs sont loggées et retournées sans basculer
4. **API bien consommée** : L'API Hugging Face Inference est appelée directement

## 🔗 Ressources

- [Hugging Face Tokens](https://huggingface.co/settings/tokens)
- [Hugging Face Inference API](https://huggingface.co/docs/api-inference/index)
- [Hugging Face Models](https://huggingface.co/models)




