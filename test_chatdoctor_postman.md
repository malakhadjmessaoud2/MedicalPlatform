# Test ChatDoctor avec Postman - Guide Rapide

## 🚀 Configuration Postman

### Étape 1 : Créer une nouvelle requête

1. Ouvrez Postman
2. Cliquez sur **"New"** → **"HTTP Request"**
3. Nommez-la : `ChatDoctor API Test`

### Étape 2 : Configurer la requête

**Method :** `POST`

**URL :**
```
http://127.0.0.1:8000/api/chatdoctor
```

**Headers :**
```
Content-Type: application/json
Accept: application/json
```

**Body :**
- Sélectionnez **"raw"**
- Choisissez **"JSON"** dans le dropdown
- Collez ce code :

```json
{
  "message": "Bonjour docteur, j'ai mal à la tête depuis hier."
}
```

### Étape 3 : Envoyer la requête

Cliquez sur **"Send"**

---

## ✅ Réponse Attendue (Succès)

```json
{
  "success": true,
  "generated_text": "Avez-vous ressenti des nausées ou une sensibilité à la lumière ?",
  "original_response": [
    {
      "generated_text": "Avez-vous ressenti des nausées ou une sensibilité à la lumière ?"
    }
  ]
}
```

---

## ❌ Réponses d'Erreur Possibles

### Erreur 401 - Token Invalide

```json
{
  "error": "API error",
  "status": 401,
  "details": "Invalid token"
}
```

**Solution :** Vérifiez votre token dans `.env`

### Erreur 503 - Modèle en Chargement

```json
{
  "error": "Modèle en chargement",
  "details": "Le modèle est en cours de chargement. Veuillez réessayer dans 30-60 secondes."
}
```

**Solution :** Attendez et réessayez

### Erreur de Validation

```json
{
  "message": "The message field is required.",
  "errors": {
    "message": ["The message field is required."]
  }
}
```

**Solution :** Vérifiez que le champ `message` est présent dans le body JSON

---

## 🧪 Exemples de Tests

### Test 1 : Question Simple

```json
{
  "message": "Bonjour docteur, j'ai mal à la tête depuis hier."
}
```

### Test 2 : Question sur la Fatigue

```json
{
  "message": "Je me sens tout le temps fatigué, est-ce grave ?"
}
```

### Test 3 : Question sur les Douleurs

```json
{
  "message": "Comment soulager les douleurs de règles ?"
}
```

---

## 📸 Capture d'Écran Postman

```
┌─────────────────────────────────────────────────┐
│ POST  http://127.0.0.1:8000/api/chatdoctor     │
├─────────────────────────────────────────────────┤
│ Headers                                          │
│ Content-Type: application/json                  │
│ Accept: application/json                        │
├─────────────────────────────────────────────────┤
│ Body (raw - JSON)                               │
│ {                                                │
│   "message": "Bonjour docteur..."               │
│ }                                                │
└─────────────────────────────────────────────────┘
```

---

## 🔍 Vérification des Logs Laravel

Après chaque requête, vérifiez les logs :

```bash
tail -f storage/logs/laravel.log
```

Vous devriez voir :
```
[2025-11-02 ...] local.INFO: Requête ChatDoctor reçue: Bonjour docteur...
[2025-11-02 ...] local.INFO: Appel ChatDoctor API avec le message: Bonjour docteur...
[2025-11-02 ...] local.INFO: Réponse ChatDoctor reçue avec succès
```

---

## ✅ Checklist Postman

- [ ] Méthode POST sélectionnée
- [ ] URL correcte : `http://127.0.0.1:8000/api/chatdoctor`
- [ ] Headers `Content-Type` et `Accept` configurés
- [ ] Body en JSON avec le champ `message`
- [ ] Token Hugging Face configuré dans `.env`
- [ ] Serveur Laravel démarré (`php artisan serve`)

