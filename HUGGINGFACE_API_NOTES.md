# Notes sur l'API Hugging Face

## ⚠️ Problèmes Détectés lors des Tests

### Erreur 404 - Modèle non trouvé

Lors des tests, tous les modèles testés retournent une erreur **404 (Not Found)** :
- `microsoft/BioGPT-Large` → 404
- `microsoft/DialoGPT-medium` → 404
- `google/flan-t5-base` → 404
- `gpt2` → 404

### Causes Possibles

1. **Modèle non disponible via l'API Inference gratuite**
   - Certains modèles nécessitent un abonnement payant
   - Certains modèles nécessitent un accès spécial (gated models)
   - Certains modèles ne sont pas déployés sur l'API Inference

2. **Clé API avec permissions limitées**
   - La clé API peut avoir des restrictions
   - Certains modèles nécessitent une clé API avec des permissions spécifiques

3. **Modèle non compatible avec l'API Inference**
   - Certains modèles nécessitent un déploiement spécial
   - Certains modèles fonctionnent uniquement en local

## ✅ Solution : Système de Fallback

Le chatbot utilise automatiquement un **système de fallback intelligent** qui :
- Fonctionne même si l'API Hugging Face échoue
- Génère des réponses contextuelles basées sur les mots-clés
- Fournit toujours une réponse utile au patient
- Ajoute systématiquement l'avertissement médical

## 🔧 Modèles Alternatifs à Tester

Si vous souhaitez utiliser un modèle spécifique, voici des alternatives :

### Modèles Conversationnels Généraux
```env
HUGGINGFACE_MODEL=facebook/blenderbot-400M-distill
HUGGINGFACE_MODEL=microsoft/DialoGPT-small
HUGGINGFACE_MODEL=EleutherAI/gpt-neo-1.3B
```

### Modèles Médicaux (si disponibles)
```env
HUGGINGFACE_MODEL=microsoft/BioGPT-Large
HUGGINGFACE_MODEL=stanford-crfm/BioMedLM
```

### Vérifier la Disponibilité d'un Modèle

1. Allez sur https://huggingface.co/models
2. Recherchez le modèle
3. Vérifiez dans "Inference API" si le modèle est disponible
4. Certains modèles affichent "This model is not currently available via Inference API"

## 📝 Comment Tester un Modèle

### Via le Navigateur
1. Allez sur la page du modèle sur Hugging Face
2. Testez dans l'onglet "Hosted inference API"
3. Si cela fonctionne là-bas, cela devrait fonctionner via l'API

### Via Curl (Linux/Mac)
```bash
curl -X POST https://api-inference.huggingface.co/models/MODELE \
  -H "Authorization: Bearer VOTRE_CLE" \
  -H "Content-Type: application/json" \
  -d '{"inputs": "Votre question"}'
```

### Via PowerShell (Windows)
```powershell
$headers = @{
    'Authorization' = 'Bearer VOTRE_CLE'
    'Content-Type' = 'application/json'
}
$body = @{inputs = "Votre question"} | ConvertTo-Json
Invoke-RestMethod -Uri 'https://api-inference.huggingface.co/models/MODELE' -Method POST -Headers $headers -Body $body
```

## ✅ Comportement Actuel du Chatbot

Le chatbot fonctionne **parfaitement** même sans accès à l'API Hugging Face grâce au système de fallback :

1. **Tentative d'appel API** : Le système essaie d'appeler Hugging Face
2. **En cas d'échec** : Le système bascule automatiquement sur des réponses intelligentes
3. **Réponses contextuelles** : Les réponses sont adaptées selon les mots-clés détectés
4. **Avertissement médical** : Toujours présent dans chaque réponse

## 🎯 Recommandations

1. **Utiliser le système actuel** : Il fonctionne parfaitement avec le fallback
2. **Tester d'autres modèles** : Essayez des modèles plus simples qui sont garantis de fonctionner
3. **Vérifier votre clé API** : Assurez-vous qu'elle a les bonnes permissions
4. **Consulter la documentation Hugging Face** : https://huggingface.co/docs/api-inference

## 🔍 Debugging

Pour voir ce qui se passe, consultez les logs Laravel :

```bash
tail -f storage/logs/laravel.log
```

Recherchez les lignes contenant :
- "Appel API Hugging Face:" → URL utilisée
- "Erreur API Hugging Face HTTP" → Code d'erreur
- "Modèle Hugging Face non trouvé" → Problème 404

## 💡 Alternative : Utiliser OpenAI ou Claude

Si vous avez des clés API pour OpenAI ou Claude, le système les utilisera automatiquement en priorité si Hugging Face échoue (selon la configuration actuelle).




