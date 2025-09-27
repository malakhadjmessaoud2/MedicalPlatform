# Système de Notation des Médecins

## Description

Ce système permet aux patients de noter les médecins avec lesquels ils ont eu des rendez-vous. Le score et le nombre d'avis sont automatiquement mis à jour selon une formule mathématique précise.

## Fonctionnalités Implémentées

### 1. Méthode `noterMedecin(Request $request)`

**Route:** `POST /patient/medecin/noter`

**Paramètres:**
- `medecin_id` (required, integer): ID du médecin à noter
- `note` (required, integer, 1-5): Note attribuée
- `avis` (optional, string, max 1000): Commentaire optionnel

**Validations:**
- Vérification que l'utilisateur est un patient
- Vérification que le médecin existe et est bien un médecin
- Vérification que le patient a eu au moins un rendez-vous avec ce médecin
- Validation des données d'entrée

### 2. Méthode Métier `ajouterAvis($medecinId, $note)`

**Logique de calcul:**
- Incrémente `nbrAvis` de +1
- Recalcule le score moyen selon la formule :
  ```
  nouveauScore = ((ancienScore × nbrAvis) + nouvelleNote) / (nbrAvis + 1)
  ```

**Sécurité:**
- Utilise `lockForUpdate()` pour éviter les conditions de course
- Validation des paramètres d'entrée
- Gestion d'erreurs complète avec logs

## Exemples d'Utilisation

### Premier Avis
- Ancien score: 0, nbrAvis: 0
- Nouvelle note: 4
- Résultat: score = 4, nbrAvis = 1

### Deuxième Avis
- Ancien score: 4, nbrAvis: 1
- Nouvelle note: 5
- Résultat: score = 4.5, nbrAvis = 2

### Troisième Avis
- Ancien score: 4.5, nbrAvis: 2
- Nouvelle note: 3
- Résultat: score = 4, nbrAvis = 3

## Réponses API

### Succès
```json
{
    "success": true,
    "message": "Médecin noté avec succès !",
    "nouveau_score": 4.5,
    "nouveau_nbr_avis": 2
}
```

### Erreur
```json
{
    "success": false,
    "message": "Vous ne pouvez noter que les médecins avec lesquels vous avez eu des rendez-vous."
}
```

## Sécurité et Contrôles

1. **Authentification:** Seuls les patients connectés peuvent noter
2. **Autorisation:** Vérification que le patient a eu des rendez-vous avec le médecin
3. **Validation:** Note entre 1 et 5, médecin existant
4. **Concurrence:** Verrouillage de la ligne pour éviter les conditions de course
5. **Audit:** Logs détaillés de toutes les opérations

## Architecture MVC Respectée

- **Model:** Utilise le modèle `User` existant avec les champs `score` et `nbrAvis`
- **View:** Interface utilisateur déjà implémentée dans `dashPatient/dossier/index.blade.php`
- **Controller:** Méthodes `noterMedecin()` et `ajouterAvis()` dans `DossierController`

## Tests Recommandés

1. Tester avec un patient qui n'a pas eu de rendez-vous avec le médecin
2. Tester avec des notes invalides (0, 6, etc.)
3. Tester la concurrence (plusieurs patients notant simultanément)
4. Vérifier la précision des calculs de score
5. Tester les cas limites (premier avis, scores décimaux)
