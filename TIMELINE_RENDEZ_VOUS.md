# Timeline des Rendez-vous du Jour

## Vue d'ensemble

La timeline des rendez-vous est une fonctionnalité intégrée dans la navbar du dashboard médecin qui affiche en temps réel :
- Le nombre total de rendez-vous du jour
- Le prochain rendez-vous à venir
- Le rendez-vous actuellement en cours

## Fonctionnalités

### 🕒 Affichage en temps réel
- **Compteur total** : Nombre de rendez-vous confirmés pour la journée
- **Prochain RDV** : Heure du prochain rendez-vous (affiché en vert)
- **RDV en cours** : Patient actuellement en consultation (affiché en orange)

### 🔄 Actualisation automatique
- Chargement initial au chargement de la page
- Actualisation toutes les 30 secondes
- Actualisation quand la page redevient visible
- Actualisation manuelle possible via l'API

### 📱 Responsive design
- Adaptation automatique aux différentes tailles d'écran
- Masquage des labels sur petits écrans
- Layout flexible selon l'espace disponible

## Structure technique

### Composants
1. **Navbar** (`resources/views/dashMedecin/navbar.blade.php`)
   - Indicateurs visuels de statut
   - Conteneurs pour chaque type d'information

2. **JavaScript** (`resources/js/timeline-rdv.js`)
   - Classe `TimelineRendezVous`
   - Gestion des appels API
   - Mise à jour du DOM

3. **API** (`/dashboard/medecin/api/rendez-vous-du-jour`)
   - Endpoint pour récupérer les rendez-vous du jour
   - Données au format JSON

### Éléments DOM
```html
<!-- Compteur total -->
<span id="rdvCount">...</span>

<!-- Prochain RDV -->
<div id="nextRdvContainer">
    <span id="nextRdvTime">...</span>
</div>

<!-- RDV en cours -->
<div id="currentRdvContainer">
    <span id="currentRdvPatient">...</span>
</div>
```

## API des rendez-vous

### Endpoint
```
GET /dashboard/medecin/api/rendez-vous-du-jour
```

### Réponse
```json
[
    {
        "id": 1,
        "date_debut": "2024-01-15T09:00:00.000000Z",
        "date_fin": "2024-01-15T09:30:00.000000Z",
        "statut": "confirmé",
        "patient": {
            "id": 1,
            "prenom": "Jean",
            "nom": "Dupont"
        }
    }
]
```

### Filtres appliqués
- Date : Aujourd'hui uniquement
- Médecin : Rendez-vous du médecin connecté
- Statut : Rendez-vous confirmés uniquement

## Logique d'affichage

### Prochain rendez-vous
- Trouve le premier RDV avec `date_debut > maintenant`
- Affiche l'heure au format HH:MM
- Masque le conteneur si aucun RDV à venir

### Rendez-vous en cours
- Trouve le RDV où `date_debut <= maintenant <= date_fin`
- Affiche le prénom du patient
- Masque le conteneur si aucun RDV en cours

### Compteur total
- Affiche le nombre total de RDV du jour
- Mise à jour en temps réel

## Styles CSS

### Couleurs
- **Bleu** : RDV du jour (compteur)
- **Vert** : Prochain RDV
- **Orange** : RDV en cours

### Animations
- **Pulse** : Indicateur "En ligne" et "RDV en cours"
- **Transitions** : Changements d'état fluides

### Responsive
- **Mobile** : Labels masqués, icônes visibles
- **Desktop** : Labels et icônes visibles
- **Tablet** : Adaptation automatique

## Tests

### Tests automatisés
```bash
php artisan test --filter=TimelineRendezVousTest
```

### Tests manuels
1. **Connexion médecin** → Vérifier l'affichage de la timeline
2. **Créer des RDV** → Vérifier la mise à jour automatique
3. **Changer d'onglet** → Vérifier l'actualisation au retour
4. **Responsive** → Tester sur différentes tailles d'écran

## Dépannage

### Problèmes courants
1. **Timeline ne s'affiche pas**
   - Vérifier que l'API `/api/rendez-vous-du-jour` fonctionne
   - Vérifier les erreurs JavaScript dans la console

2. **Données non mises à jour**
   - Vérifier l'intervalle d'actualisation (30s)
   - Vérifier la visibilité de la page

3. **Erreurs d'affichage**
   - Vérifier que les éléments DOM existent
   - Vérifier les permissions d'accès à l'API

### Debug
```javascript
// Dans la console du navigateur
console.log(window.timelineRdv.getStatus());
window.timelineRdv.refresh();
```

## Personnalisation

### Modifier l'intervalle d'actualisation
```javascript
// Dans timeline-rdv.js
setInterval(() => this.loadRendezVousDuJour(), 60000); // 1 minute
```

### Ajouter de nouveaux indicateurs
```html
<!-- Dans navbar.blade.php -->
<div class="flex items-center space-x-2 bg-purple-50 px-3 py-1.5 rounded-full">
    <span class="text-sm font-medium text-purple-700">Nouvel indicateur</span>
</div>
```

### Modifier les couleurs
```css
/* Dans app.css */
.timeline-rdv-custom {
    @apply bg-custom-50 text-custom-700;
}
```

## Performance

### Optimisations
- **Debouncing** : Éviter les appels API trop fréquents
- **Cache** : Mise en cache des données pendant 30s
- **Lazy loading** : Chargement uniquement quand nécessaire

### Métriques
- **Temps de réponse API** : < 200ms
- **Fréquence d'actualisation** : 30s
- **Taille des données** : < 10KB par appel

## Sécurité

### Authentification
- Route protégée par middleware `auth`
- Vérification du rôle `medecin`
- Isolation des données par médecin

### Validation
- Vérification des dates
- Filtrage des statuts
- Sanitisation des données patient

## Maintenance

### Logs
- Erreurs d'API dans la console
- Erreurs de chargement des données
- Statut de la timeline

### Monitoring
- Disponibilité de l'API
- Temps de réponse
- Erreurs JavaScript

### Mises à jour
- Vérifier la compatibilité des navigateurs
- Tester les nouvelles fonctionnalités
- Valider la performance

