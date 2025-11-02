# Guide d'Intégration Mistral-7B-Instruct-v0.2

## ✅ Migration vers Mistral-7B-Instruct-v0.2

Le chatbot utilise maintenant le modèle **Mistral-7B-Instruct-v0.2** au lieu de ChatDoctor.

Documentation officielle : [https://huggingface.co/mistralai/Mistral-7B-Instruct-v0.2](https://huggingface.co/mistralai/Mistral-7B-Instruct-v0.2)

---

## 🔧 Configuration

### Fichier .env

```env
HUGGINGFACE_API_KEY=hf_votre_token_ici
HUGGINGFACE_MODEL=mistralai/Mistral-7B-Instruct-v0.2
```

### Fichier config/services.php

✅ **Déjà configuré** avec :
```php
'huggingface' => [
    'api_key' => env('HUGGINGFACE_API_KEY'),
    'model' => env('HUGGINGFACE_MODEL', 'mistralai/Mistral-7B-Instruct-v0.2'),
    'api_url' => 'https://api-inference.huggingface.co/models/',
],
```

---

## 📝 Format d'Instruction Mistral

Selon la [documentation officielle](https://huggingface.co/mistralai/Mistral-7B-Instruct-v0.2), Mistral utilise un format d'instruction spécifique :

```
<s>[INST] question [/INST]
```

### Exemple

**Question :** "Bonjour, qui es-tu ?"

**Prompt formaté :**
```
<s>[INST] Bonjour, qui es-tu ? [/INST]
```

---

## 🔄 Modifications Apportées

### 1. Service ChatDoctorService.php

✅ **Mis à jour** pour :
- Utiliser le modèle Mistral depuis la configuration
- Formater automatiquement le prompt selon le format Mistral
- Gérer les paramètres spécifiques (max_new_tokens, temperature, top_p)

**Fonction `formatMistralPrompt()` :**
```php
private function formatMistralPrompt($message)
{
    return "<s>[INST] " . trim($message) . " [/INST]";
}
```

### 2. Route Simple dans routes/web.php

✅ **Route ajoutée** : `POST /chatbot`

Cette route :
- Utilise directement l'API Hugging Face
- Formate le prompt selon le format Mistral
- Gère les erreurs appropriément
- Retourne `generated_text` extrait

**Exemple d'utilisation :**
```javascript
fetch('/chatbot', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({
        message: "Bonjour, qui es-tu ?"
    })
})
```

---

## 🧪 Test avec Postman

### Configuration

**Method :** `POST`
**URL :** `http://127.0.0.1:8000/chatbot`

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
  "original_response": [
    {
      "generated_text": "Réponse générée par Mistral..."
    }
  ]
}
```

---

## 📊 Paramètres de Génération

Les paramètres utilisés pour Mistral :

```php
'parameters' => [
    'max_new_tokens' => 512,      // Maximum de tokens à générer
    'temperature' => 0.7,          // Créativité (0.0 = déterministe, 1.0 = créatif)
    'top_p' => 0.95,              // Noyau de probabilité
    'return_full_text' => false   // Ne pas retourner le prompt original
]
```

---

## 🔍 Différences avec ChatDoctor

| Aspect | ChatDoctor | Mistral-7B-Instruct |
|--------|-----------|---------------------|
| Format prompt | Simple texte | `<s>[INST] texte [/INST]` |
| Paramètres | Basiques | Optimisés (temperature, top_p) |
| Timeout | 60s | 90s |
| max_new_tokens | Par défaut | 512 |

---

## 🚀 Utilisation

### Via le Service (Recommandé)

```php
use App\Services\ChatDoctorService;

$service = new ChatDoctorService();
$response = $service->ask("Bonjour, qui es-tu ?");

if (!isset($response['error'])) {
    // Extraire generated_text
    $generatedText = $response[0]['generated_text'] ?? $response['generated_text'];
    echo $generatedText;
}
```

### Via la Route Simple

```javascript
// JavaScript
const response = await fetch('/chatbot', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({
        message: "Votre question ici"
    })
});

const data = await response.json();
if (data.success) {
    console.log(data.generated_text);
}
```

---

## 🔧 Troubleshooting

### Erreur : Format de réponse inattendu

**Cause :** Le format de réponse de Mistral peut varier

**Solution :** Le service gère automatiquement plusieurs formats :
- `[{"generated_text": "..."}]`
- `{"generated_text": "..."}`
- `{"answer": "..."}`

### Erreur : Modèle en chargement (503)

**Cause :** Le modèle est en cours de chargement (première utilisation)

**Solution :** Attendez 30-60 secondes et réessayez

### Erreur : Token invalide (401)

**Cause :** Token Hugging Face invalide ou expiré

**Solution :**
1. Créez un nouveau token sur https://huggingface.co/settings/tokens
2. Choisissez "Fine-Grained" avec permission `inference:provider:access`
3. Mettez à jour `.env` avec le nouveau token

---

## ✅ Checklist

- [x] Configuration mise à jour dans `config/services.php`
- [x] Service `ChatDoctorService` modifié pour Mistral
- [x] Format Mistral implémenté (`<s>[INST] ... [/INST]`)
- [x] Route simple ajoutée dans `routes/web.php`
- [x] Paramètres de génération optimisés
- [x] Gestion d'erreurs améliorée

---

## 📚 Ressources

- [Documentation Mistral-7B-Instruct-v0.2](https://huggingface.co/mistralai/Mistral-7B-Instruct-v0.2)
- [Format d'Instruction Mistral](https://huggingface.co/mistralai/Mistral-7B-Instruct-v0.2#instruction-format)
- [API Inference Hugging Face](https://huggingface.co/docs/api-inference)

---

## 🎯 Avantages de Mistral-7B-Instruct

1. **Performance améliorée** : Modèle plus récent et optimisé
2. **Format standardisé** : Format d'instruction clair et documenté
3. **Meilleure compréhension** : Context window de 32k tokens
4. **Réponses plus cohérentes** : Fine-tuned pour les instructions

---

## 🔄 Migration depuis ChatDoctor

Si vous aviez des données avec ChatDoctor :
- Les anciennes réponses restent dans la base de données
- Les nouvelles questions utilisent automatiquement Mistral
- Aucune modification de la structure de base de données nécessaire

