# Modèles Hugging Face Testés - API Inference 2025

## ⚠️ Problème rencontré

Le modèle `google/flan-t5-large` retourne **404 (Not Found)** via l'API Inference publique.

## ✅ Modèle par défaut : `gpt2`

Le modèle **`gpt2`** est utilisé par défaut car il est **toujours disponible** via l'API Inference publique.

### Configuration actuelle

```env
HUGGINGFACE_API_KEY=hf_votre_token_ici
HUGGINGFACE_MODEL=gpt2
```

## 🔧 Modèles médicaux à tester

Si vous souhaitez utiliser un modèle médical, testez ces modèles dans votre `.env` :

### Option 1 : Modèle médical spécialisé

```env
HUGGINGFACE_MODEL=AventIQ-AI/t5-medical-chatbot
```

**Note** : Ce modèle peut nécessiter "inference enabled" ou ne pas être disponible via l'API publique.

### Option 2 : Modèles conversationnels généraux

```env
# Facebook Blenderbot (petit, rapide)
HUGGINGFACE_MODEL=facebook/blenderbot-400M-distill

# Microsoft DialoGPT
HUGGINGFACE_MODEL=microsoft/DialoGPT-small
```

### Option 3 : Modèles GPT simples (toujours disponibles)

```env
# GPT2 standard
HUGGINGFACE_MODEL=gpt2

# GPT2 léger
HUGGINGFACE_MODEL=distilgpt2
```

## 🧪 Comment tester un modèle

### Méthode 1 : Via curl (PowerShell)

```powershell
$model = "gpt2"
$token = "hf_votre_token"
$body = @{
    inputs = "Question médicale : J'ai mal à la tête"
    parameters = @{
        max_new_tokens = 100
    }
} | ConvertTo-Json

Invoke-WebRequest -Uri "https://api-inference.huggingface.co/models/$model" `
    -Method POST `
    -Headers @{"Authorization"="Bearer $token"; "Content-Type"="application/json"} `
    -Body $body
```

### Méthode 2 : Via Laravel Tinker

```bash
php artisan tinker
```

```php
use Illuminate\Support\Facades\Http;

$model = 'gpt2';
$token = env('HUGGINGFACE_API_KEY');

$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $token,
    'Content-Type' => 'application/json'
])->post("https://api-inference.huggingface.co/models/$model", [
    'inputs' => 'Question médicale : J\'ai mal à la tête',
    'parameters' => [
        'max_new_tokens' => 100
    ]
]);

if ($response->successful()) {
    echo "✅ Modèle fonctionne!\n";
    print_r($response->json());
} else {
    echo "❌ Erreur: " . $response->status() . "\n";
    echo $response->body();
}
```

## 📝 Liste des modèles à essayer

### Modèles qui fonctionnent généralement :

1. **gpt2** ✅ (toujours disponible)
2. **distilgpt2** ✅ (toujours disponible)
3. **facebook/blenderbot-400M-distill** ⚠️ (peut nécessiter inference enabled)
4. **microsoft/DialoGPT-small** ⚠️ (peut nécessiter inference enabled)

### Modèles médicaux à tester :

1. **AventIQ-AI/t5-medical-chatbot** ⚠️ (peut nécessiter inference enabled)
2. **microsoft/BioGPT** ⚠️ (peut nécessiter inference enabled)

## ⚠️ Notes importantes

1. **API Inference publique** : Depuis mi-2024, tous les modèles ne sont pas disponibles via l'API Inference publique gratuite
2. **Inference enabled** : Certains modèles nécessitent que l'auteur active "inference" explicitement
3. **Token valide** : Assurez-vous que votre token Hugging Face est valide (permissions "Read")

## 🔍 Vérifier qu'un modèle est disponible

1. Visitez la page du modèle sur Hugging Face : `https://huggingface.co/{MODEL_NAME}`
2. Vérifiez qu'il a une section "Deploy" ou "Inference"
3. Testez via l'interface Hugging Face avant de l'utiliser dans votre code

## ✅ Solution actuelle

Le système utilise **`gpt2`** par défaut car c'est le modèle le plus fiable et toujours disponible via l'API Inference publique.

Pour utiliser un autre modèle, configurez-le dans `.env` :

```env
HUGGINGFACE_MODEL=votre_modele_ici
```

Puis testez-le avant de l'utiliser en production.




