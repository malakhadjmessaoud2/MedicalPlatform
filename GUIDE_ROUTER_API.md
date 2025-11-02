# Guide d'Intégration Mistral via Router API (OpenAI-compatible)

## ✅ Migration vers Router API

Le chatbot utilise maintenant l'**API Router de Hugging Face** (OpenAI-compatible) au lieu de l'API Inference classique.

**Avantages :**
- ✅ Format standardisé (compatible OpenAI)
- ✅ Plus simple à utiliser (format messages avec roles)
- ✅ Plus fiable et rapide
- ✅ Gestion automatique du contexte conversationnel

---

## 🔧 Configuration

### Fichier .env

```env
HUGGINGFACE_API_KEY=hf_votre_token_ici
HUGGINGFACE_MODEL=mistralai/Mistral-7B-Instruct-v0.2
HUGGINGFACE_USE_ROUTER=true  # Utiliser le Router par défaut (recommandé)
```

### Fichier config/services.php

✅ **Déjà configuré** avec :
```php
'huggingface' => [
    'api_key' => env('HUGGINGFACE_API_KEY'),
    'model' => env('HUGGINGFACE_MODEL', 'mistralai/Mistral-7B-Instruct-v0.2'),
    'router_url' => 'https://router.huggingface.co/v1',
    'use_router' => env('HUGGINGFACE_USE_ROUTER', true),
],
```

---

## 📝 Format Router API (OpenAI-compatible)

### Format de Requête

```json
{
  "model": "mistralai/Mistral-7B-Instruct-v0.2:featherless-ai",
  "messages": [
    {
      "role": "user",
      "content": "Bonjour docteur, j'ai mal à la tête depuis hier."
    }
  ],
  "max_tokens": 512,
  "temperature": 0.7,
  "top_p": 0.95
}
```

### Format de Réponse

```json
{
  "choices": [
    {
      "message": {
        "role": "assistant",
        "content": "Réponse générée par Mistral..."
      }
    }
  ]
}
```

---

## 🧪 Test avec Postman

### Configuration Postman

**Method :** `POST`
**URL :** `http://127.0.0.1:8000/chatbot-simple`

**Headers :**
```
Content-Type: application/json
Accept: application/json
```

**Body (JSON) :**
```json
{
  "message": "Bonjour docteur, j'ai mal à la tête depuis hier."
}
```

### Réponse Attendue

```json
{
  "success": true,
  "generated_text": "Réponse générée par Mistral...",
  "original_response": {
    "choices": [
      {
        "message": {
          "role": "assistant",
          "content": "Réponse générée par Mistral..."
        }
      }
    ]
  }
}
```

---

## 📊 Comparaison des APIs

| Aspect | API Inference Classique | Router API (OpenAI-compatible) |
|--------|-------------------------|--------------------------------|
| Format | `inputs: "<s>[INST] ..."` | `messages: [{role, content}]` |
| Endpoint | `/models/{model}` | `/v1/chat/completions` |
| Modèle | `mistralai/Mistral-7B-Instruct-v0.2` | `mistralai/Mistral-7B-Instruct-v0.2:featherless-ai` |
| Réponse | `[{"generated_text": "..."}]` | `{"choices": [{"message": {"content": "..."}}]}` |
| Complexité | Plus complexe | Plus simple |
| Fiabilité | Variable | Plus fiable |

---

## 🔄 Code Utilisé

### Via Service ChatDoctorService

```php
$service = new ChatDoctorService();
$response = $service->ask("Bonjour, qui es-tu ?");

// Format de réponse : [['generated_text' => '...']]
if (!isset($response['error'])) {
    $generatedText = $response[0]['generated_text'] ?? $response['generated_text'];
}
```

### Via Service ChatBotService

```php
$service = new ChatBotService();
$response = $service->generateResponse("Bonjour, qui es-tu ?", []);

// Format de réponse : ['success' => true, 'message' => '...']
if ($response['success']) {
    echo $response['message'];
}
```

---

## 🔧 Fallback vers API Classique

Si vous voulez utiliser l'API classique au lieu du Router :

```env
HUGGINGFACE_USE_ROUTER=false
```

Le système utilisera automatiquement l'API Inference classique avec le format Mistral (`<s>[INST] ... [/INST]`).

---

## 📚 Documentation

- [Hugging Face Router API](https://huggingface.co/docs/api-inference/router)
- [Mistral-7B-Instruct-v0.2](https://huggingface.co/mistralai/Mistral-7B-Instruct-v0.2)
- [Featherless AI Provider](https://huggingface.co/docs/api-inference/inference_providers)

---

## ✅ Checklist

- [x] Router API configuré dans `config/services.php`
- [x] `ChatDoctorService` utilise le Router
- [x] `ChatBotService` utilise le Router
- [x] Route `/chatbot-simple` mise à jour
- [x] Format de réponse extrait correctement
- [x] Gestion d'erreurs améliorée

---

## 🎯 Avantages du Router API

1. **Format Standardisé** : Compatible avec le format OpenAI
2. **Plus Simple** : Pas besoin de formater les prompts manuellement
3. **Meilleure Performance** : Optimisé pour la production
4. **Gestion Automatique** : Le Router gère automatiquement le contexte
5. **Multi-Provider** : Support de différents providers (featherless-ai, etc.)

