# 📘 Guide Complet : Création d'un Token Hugging Face Valide

## ❌ Problème Actuel

Votre nouveau token (`hf_wyaWBcG...`) retourne toujours **401 (Invalid credentials)**.

Cela signifie que le token n'a pas été créé correctement ou a été mal copié.

## ✅ Solution Pas à Pas

### Étape 1 : Aller sur la page des tokens

1. **Allez sur** : https://huggingface.co/
2. **Connectez-vous** (ou créez un compte si nécessaire)
3. **Cliquez sur votre profil** (en haut à droite)
4. **Sélectionnez "Settings"**
5. **Dans le menu gauche, cliquez sur "Access Tokens"**

### Étape 2 : Vérifier les tokens existants

1. **Regardez la liste des tokens existants**
2. Si vous avez créé un token récemment mais qu'il ne fonctionne pas :
   - **Supprimez-le** (icône poubelle)
   - **Recréez-en un nouveau**

### Étape 3 : Créer un nouveau token (ATTENTION AUX DÉTAILS)

1. **Cliquez sur "New token"** (bouton en haut à droite)
2. **Remplissez le formulaire** :
   - **Token name** : `MedicalPlatform` (ou autre nom de votre choix)
   - **Token type** : ✅ **Sélectionnez "Read"** (IMPORTANT !)
3. **Cliquez sur "Generate token"**

### Étape 4 : COPIER LE TOKEN (TRÈS IMPORTANT)

⚠️ **ATTENTION** : Le token ne sera affiché **QU'UNE SEULE FOIS** !

1. **Sélectionnez TOUT le token** (faites glisser de gauche à droite)
2. **Copiez-le** (Ctrl+C ou Clic droit > Copier)
3. **Vérifiez qu'il commence par `hf_`**
4. **Vérifiez qu'il n'y a PAS d'espaces** avant ou après

### Étape 5 : Mettre à jour votre .env

1. **Ouvrez votre fichier `.env`**
2. **Trouvez la ligne** : `HUGGINGFACE_API_KEY=`
3. **Remplacez l'ancienne valeur** par le nouveau token :
   ```env
   HUGGINGFACE_API_KEY=hf_VOTRE_TOKEN_COPIÉ_ICI
   ```

⚠️ **VÉRIFICATIONS IMPORTANTES** :
- ✅ Pas d'espaces avant ou après le token
- ✅ Le token commence bien par `hf_`
- ✅ Le token est complet (environ 37 caractères)
- ✅ Pas de guillemets autour du token
- ✅ Pas de saut de ligne

### Étape 6 : Sauvegarder et tester

1. **Sauvegardez le fichier `.env`**
2. **Exécutez** :
   ```bash
   php artisan config:clear
   ```
3. **Testez le chatbot**

## 🔍 Vérification du Token

Pour vérifier que votre token est correct, créez un fichier `check_token.php` :

```php
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$token = config('services.huggingface.api_key');

echo "Token: " . substr($token, 0, 15) . "...\n";
echo "Longueur: " . strlen($token) . " caractères\n";
echo "Commence par hf_: " . (str_starts_with($token, 'hf_') ? 'OUI ✅' : 'NON ❌') . "\n";

$response = \Illuminate\Support\Facades\Http::withHeaders([
    'Authorization' => 'Bearer ' . $token
])->get('https://huggingface.co/api/whoami');

if ($response->successful()) {
    echo "✅ TOKEN VALIDE!\n";
    print_r($response->json());
} else {
    echo "❌ TOKEN INVALIDE (Status: " . $response->status() . ")\n";
    echo $response->body() . "\n";
}
```

Exécutez : `php check_token.php`

## ⚠️ Erreurs Communes

### 1. Token avec espaces
```env
# ❌ MAUVAIS
HUGGINGFACE_API_KEY= hf_abc123...
HUGGINGFACE_API_KEY=hf_abc123... 

# ✅ BON
HUGGINGFACE_API_KEY=hf_abc123...
```

### 2. Token incomplet (tronqué)
Vérifiez que le token est complet (environ 37-40 caractères)

### 3. Token d'un autre compte
Assurez-vous d'être connecté au bon compte Hugging Face

### 4. Token sans permissions "Read"
Lors de la création, sélectionnez bien "Read"

## ✅ Après avoir configuré le token correctement

Le chatbot devrait automatiquement :
1. Tester le modèle configuré
2. Si 404, essayer `gpt2`
3. Si 404, essayer `distilgpt2`
4. Si 404, essayer `EleutherAI/gpt-neo-125M`
5. Utiliser le premier qui fonctionne

## 📝 Résumé

**Le problème est que votre token est invalide**, pas les modèles.

**Créez un nouveau token en suivant exactement ces étapes**, et tout fonctionnera !

Une fois le token valide configuré, **vous n'aurez plus besoin de le changer** - vous pourrez utiliser n'importe quel modèle avec le même token.




