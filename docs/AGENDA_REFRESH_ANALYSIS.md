# Analyse : Rafraîchissement de l'Agenda

## Réponse Directe

**NON, l'agenda n'a PAS de rafraîchissement automatique périodique** comme la timeline de la navbar.

---

## Mécanismes de Rafraîchissement Actuels

### 1. Chargement Initial

L'agenda se charge **une seule fois** au chargement de la page :

```javascript
// resources/js/agenda.js
document.addEventListener('DOMContentLoaded', function() {
    const calendar = new Calendar(calendarEl, {
        eventSources: [{
            url: '/api/medecin/rendez-vous',
            method: 'GET',
            // ...
        }],
        lazyFetching: false,  // Charge les événements à chaque changement de vue
    });
    calendar.render();
});
```

**Comportement** :
- ✅ Chargement initial au `DOMContentLoaded`
- ✅ Utilisation de `eventSources` de FullCalendar
- ✅ Appel API vers `/api/medecin/rendez-vous`

### 2. Rafraîchissement lors du Changement de Vue

FullCalendar recharge automatiquement les événements lors du changement de vue grâce à `lazyFetching: false` :

```javascript
lazyFetching: false,  // Force le rechargement à chaque changement de vue
```

**Vues disponibles** :
- `dayGridMonth` (Mois)
- `timeGridWeek` (Semaine)
- `timeGridDay` (Jour)
- `listWeek` (Liste)

**Comportement** :
- ✅ Rechargement automatique lors du changement de vue
- ✅ Appel API à chaque changement

### 3. Rafraîchissement après Actions

L'agenda se rafraîchit **manuellement** après certaines actions :

#### a) Après une Mise à Jour (Drag & Drop, Resize)

```javascript
// resources/js/agenda.js (ligne 212-248)
function handleEventChange(event) {
    fetch(`/api/medecin/rendez-vous/${eventId}`, {
        method: 'PUT',
        // ...
    })
    .then(data => {
        showNotification('Rendez-vous mis à jour avec succès', 'success');
    })
    .catch(error => {
        calendar.refetchEvents(); // Rechargement en cas d'erreur
    });
}
```

#### b) Après une Mise à Jour de Statut

```javascript
// resources/js/agenda.js (ligne 607-657)
async function updateEventStatus(eventId, newStatus) {
    const response = await fetch(`/api/medecin/rendez-vous/${eventId}/statut`, {
        method: 'PUT',
        // ...
    });
    
    if (window.calendar) {
        window.calendar.refetchEvents(); // Rafraîchissement manuel
    }
    
    // Rechargement complet de la page après 1 seconde
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}
```

#### c) Après une Suppression

```javascript
// resources/js/agenda.js (ligne 660-711)
async function deleteEvent(eventId) {
    const response = await fetch(`/api/medecin/rendez-vous/${eventId}`, {
        method: 'DELETE',
        // ...
    });
    
    if (window.calendar) {
        const event = window.calendar.getEventById(eventId);
        if (event) {
            event.remove(); // Suppression locale
        }
        window.calendar.refetchEvents(); // Rafraîchissement manuel
    }
    
    // Rechargement complet de la page après 1 seconde
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}
```

---

## Comparaison : Timeline vs Agenda

| Caractéristique | Timeline (Navbar) | Agenda (FullCalendar) |
|----------------|-------------------|----------------------|
| **Rafraîchissement automatique** | ✅ Oui (toutes les 60s) | ❌ Non |
| **Chargement initial** | ✅ Oui | ✅ Oui |
| **Rafraîchissement changement vue** | N/A | ✅ Oui |
| **Rafraîchissement après action** | N/A | ✅ Oui (manuel) |
| **Mécanisme** | `setInterval(60000)` | `refetchEvents()` / `reload()` |

---

## Problèmes Potentiels

### 1. Données Obsolètes

**Problème** : Si un autre utilisateur (patient, autre médecin) modifie un rendez-vous, l'agenda ne se met pas à jour automatiquement.

**Exemple** :
- Médecin A ouvre l'agenda à 10h00
- Patient annule un rendez-vous à 10h05
- Médecin A voit toujours le rendez-vous dans son agenda jusqu'à ce qu'il :
  - Change de vue
  - Rafraîchisse manuellement la page
  - Effectue une action qui déclenche `refetchEvents()`

### 2. Pas de Synchronisation Temps Réel

Contrairement à la timeline qui se rafraîchit automatiquement, l'agenda ne bénéficie pas de :
- ✅ WebSocket pour les mises à jour en temps réel
- ✅ Rafraîchissement périodique automatique
- ✅ Notifications visuelles des changements

---

## Solutions Proposées

### Solution 1 : Ajouter un Rafraîchissement Automatique Périodique

Ajouter un `setInterval` similaire à la timeline :

```javascript
// resources/js/agenda.js
document.addEventListener('DOMContentLoaded', function() {
    // ... Initialisation du calendrier ...
    
    calendar.render();
    window.calendar = calendar;
    
    // Rafraîchissement automatique toutes les 60 secondes
    setInterval(() => {
        if (window.calendar) {
            window.calendar.refetchEvents();
            console.log('[Agenda] Rafraîchissement automatique');
        }
    }, 60000); // 60 secondes
});
```

**Avantages** :
- ✅ Données toujours à jour
- ✅ Détection automatique des changements
- ✅ Cohérence avec la timeline

**Inconvénients** :
- ⚠️ Charge serveur supplémentaire
- ⚠️ Consommation réseau

### Solution 2 : Utiliser WebSocket pour les Mises à Jour Temps Réel

Intégrer Laravel Echo pour recevoir les événements en temps réel :

```javascript
// resources/js/agenda.js
if (window.Echo) {
    // Écouter les événements de création
    window.Echo.channel('rendez-vous')
        .listen('.create', (e) => {
            if (window.calendar) {
                window.calendar.refetchEvents();
            }
        })
        .listen('.RendezVousModifie', (e) => {
            if (window.calendar && e.action === 'updated') {
                window.calendar.refetchEvents();
            }
        });
}
```

**Avantages** :
- ✅ Mises à jour instantanées
- ✅ Pas de polling inutile
- ✅ Économie de ressources

**Inconvénients** :
- ⚠️ Nécessite Laravel Reverb configuré
- ⚠️ Plus complexe à implémenter

### Solution 3 : Rafraîchissement Conditionnel

Rafraîchir uniquement si nécessaire (pendant les heures de travail) :

```javascript
function shouldRefreshAgenda() {
    const now = new Date();
    const hour = now.getHours();
    
    // Rafraîchir uniquement pendant les heures de travail (8h-19h)
    if (hour >= 8 && hour <= 19) {
        return true;
    }
    return false;
}

setInterval(() => {
    if (shouldRefreshAgenda() && window.calendar) {
        window.calendar.refetchEvents();
    }
}, 60000);
```

**Avantages** :
- ✅ Économie de ressources en dehors des heures de travail
- ✅ Rafraîchissement intelligent

---

## Recommandation

### Option Recommandée : Solution 1 + Solution 2 (Hybride)

**Implémentation** :

1. **Rafraîchissement automatique périodique** (60 secondes) pour garantir la cohérence
2. **WebSocket pour les mises à jour instantanées** pour les événements critiques

```javascript
// resources/js/agenda.js
document.addEventListener('DOMContentLoaded', function() {
    // ... Initialisation du calendrier ...
    
    calendar.render();
    window.calendar = calendar;
    
    // 1. Rafraîchissement automatique périodique
    setInterval(() => {
        if (window.calendar) {
            window.calendar.refetchEvents();
        }
    }, 60000); // 60 secondes
    
    // 2. WebSocket pour les mises à jour temps réel
    if (window.Echo) {
        window.Echo.channel('rendez-vous')
            .listen('.create', () => {
                if (window.calendar) {
                    window.calendar.refetchEvents();
                }
            })
            .listen('.RendezVousModifie', (e) => {
                if (window.calendar && ['updated', 'deleted'].includes(e.action)) {
                    window.calendar.refetchEvents();
                }
            });
    }
});
```

**Avantages** :
- ✅ Meilleur des deux mondes
- ✅ Mises à jour instantanées via WebSocket
- ✅ Fallback périodique si WebSocket échoue
- ✅ Cohérence avec la timeline

---

## Code Actuel : Points Clés

### Configuration FullCalendar

```javascript
{
    lazyFetching: false,  // Recharge à chaque changement de vue
    progressiveEventRendering: true,  // Rendu progressif
    rerenderDelay: 150  // Délai avant re-rendu
}
```

### Méthodes de Rafraîchissement Disponibles

1. **`calendar.refetchEvents()`** : Recharge tous les événements depuis l'API
2. **`window.location.reload()`** : Recharge complète de la page
3. **`event.remove()`** : Suppression locale d'un événement

### Endpoint API

```
GET /api/medecin/rendez-vous
```

**Contrôleur** : `Medecin\RendezVousController@getEvenements`

**Retour** : JSON avec les événements au format FullCalendar

---

## Conclusion

**État Actuel** :
- ❌ Pas de rafraîchissement automatique périodique
- ✅ Rafraîchissement au chargement initial
- ✅ Rafraîchissement lors du changement de vue
- ✅ Rafraîchissement manuel après certaines actions

**Recommandation** :
- 🔄 Ajouter un rafraîchissement automatique toutes les 60 secondes
- 🔄 Intégrer WebSocket pour les mises à jour temps réel
- 🔄 Maintenir la cohérence avec la timeline

**Impact** :
- 📈 Amélioration de l'expérience utilisateur
- 📈 Données toujours à jour
- 📈 Cohérence entre timeline et agenda

---

**Documentation générée le** : {{ date('Y-m-d') }}
**Version** : 1.0.0


