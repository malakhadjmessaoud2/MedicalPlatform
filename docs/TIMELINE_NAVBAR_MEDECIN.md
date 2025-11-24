# Documentation : Rafraîchissement de la Timeline dans la Navbar Médecin

## Vue d'ensemble

La timeline dans la navbar du médecin affiche les rendez-vous de la journée (8h-17h) et se rafraîchit automatiquement toutes les 60 secondes pour maintenir les données à jour.

---

## Architecture du Système

### Schéma de Fonctionnement

```
┌─────────────────────────────────────────────────────────────┐
│                    NAVBAR MÉDECIN                           │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Timeline Container (HTML)                           │  │
│  │  - Affichage des créneaux 8h-17h                      │  │
│  │  - Indicateurs visuels (actif, en retard, libre)     │  │
│  │  - Boutons de scroll horizontal                       │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                          ↕ JavaScript
┌─────────────────────────────────────────────────────────────┐
│              navbar-timeline.js                             │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  window.loadTimeline()                                │  │
│  │  - Appel API toutes les 60 secondes                  │  │
│  │  - Mise à jour du DOM                                │  │
│  │  - Gestion des erreurs                               │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                          ↕ HTTP GET
┌─────────────────────────────────────────────────────────────┐
│              API: /api/medecin/timeline                     │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  TimelineController@getTimelineData()                │  │
│  │  - Récupération des RDV du jour                      │  │
│  │  - Filtrage par statut                               │  │
│  │  - Construction du timeline                           │  │
│  │  - Retour JSON                                       │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                          ↕ Query
┌─────────────────────────────────────────────────────────────┐
│              Base de Données                                │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Table: rendez_vous                                  │  │
│  │  - Filtre: date_debut = aujourd'hui                  │  │
│  │  - Filtre: medecin_id = utilisateur connecté         │  │
│  │  - Exclut: pending, cancelled, rejected             │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

---

## Composants

### 1. Frontend : JavaScript (`navbar-timeline.js`)

#### Initialisation

```javascript
// resources/js/dashboard/navbar-timeline.js
export function initNavbarTimeline() {
    document.addEventListener('DOMContentLoaded', function() {
        // Chargement initial
        window.loadTimeline();
        
        // Rafraîchissement automatique toutes les 60 secondes
        setInterval(window.loadTimeline, 60000);
    });
}
```

**Points clés** :
- ✅ Chargement immédiat au chargement de la page
- ✅ Rafraîchissement automatique toutes les **60 secondes** (1 minute)
- ✅ Utilisation de `setInterval` pour la périodicité

#### Fonction de Chargement

```javascript
window.loadTimeline = function() {
    fetch('/api/medecin/timeline', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            console.error('Erreur API:', data.error);
            showTimelineError(data.error);
        } else {
            updateTimeline(data);
        }
    })
    .catch(error => {
        console.error('Erreur lors du chargement du timeline:', error);
        showTimelineError(error.message);
    });
};
```

**Fonctionnalités** :
- ✅ Appel API REST vers `/api/medecin/timeline`
- ✅ Gestion des erreurs HTTP
- ✅ Gestion des erreurs réseau
- ✅ Mise à jour du DOM en cas de succès
- ✅ Affichage d'erreur en cas d'échec

#### Mise à Jour du DOM

```javascript
function updateTimeline(data) {
    const timelineContent = document.getElementById('timeline-content');
    const currentDate = document.getElementById('current-date');
    
    // Mise à jour de la date
    if (currentDate) currentDate.textContent = data.current_date;
    
    // Construction du HTML pour chaque créneau horaire (8h-17h)
    let timelineHTML = '';
    for (let hour = 8; hour <= 17; hour++) {
        const timeSlot = data.timeline.find(slot => slot.hour === hour);
        const isCurrentHour = hour === data.current_hour;
        const isPast = hour < data.current_hour;
        
        // Récupération du rendez-vous pour ce créneau
        const rdv = timeSlot ? (() => {
            const raw = (timeSlot.rendez_vous || []);
            // Filtrage des statuts exclus
            const list = raw.filter(r => {
                const st = (r.statut || r.status || r.state || '').toLowerCase();
                return !['pending', 'cancelled', 'rejected'].includes(st);
            });
            // Tri par priorité
            list.sort((a, b) => {
                const priorityOrder = { 'payed': 0, 'confirmed': 1, 'confirmé': 1, 'completed': 2 };
                const sa = (a.statut || a.status || a.state || '').toLowerCase();
                const sb = (b.statut || b.status || b.state || '').toLowerCase();
                return (priorityOrder[sa] ?? 99) - (priorityOrder[sb] ?? 99);
            });
            return list[0] || null;
        })() : null;
        
        // Génération du HTML pour ce créneau
        timelineHTML += `
            <div class="timeline-slot ${isCurrentHour ? 'current' : isPast ? 'past' : 'future'}" data-hour="${hour}">
                ${rdv ? `
                    <div class="slot-chip enhanced ${rdv.is_active ? 'active' : ''} ${rdv.is_late ? 'late' : ''}">
                        <!-- Avatar patient -->
                        <div class="slot-avatar-container">
                            <img src="${rdv.patient_photo}" class="slot-avatar" alt="Patient">
                            ${rdv.is_active ? '<div class="status-indicator active"></div>' : ''}
                        </div>
                        <!-- Informations -->
                        <div class="slot-info">
                            <span class="slot-name">${rdv.patient_name}</span>
                            <span class="slot-time">${rdv.start_time}</span>
                        </div>
                        <!-- Lien consultation si disponible -->
                        ${rdv.has_consultation_link ? `
                            <div class="meet-icon-container active">
                                <img src="..." onclick="openConsultationLink('${rdv.lien_en_ligne}', ${rdv.id})">
                            </div>
                        ` : ''}
                    </div>
                ` : `
                    <div class="slot-chip free">
                        <div class="slot-info">
                            <span class="slot-time">${hour.toString().padStart(2, '0')}:00</span>
                            <span class="slot-name">Libre</span>
                        </div>
                    </div>
                `}
            </div>
        `;
    }
    
    // Injection du HTML dans le DOM
    timelineContent.innerHTML = timelineHTML;
    
    // Scroll automatique vers l'heure actuelle
    setTimeout(() => { scrollToCurrentHour(data.current_hour); }, 200);
    
    // Animations d'apparition
    const slots = timelineContent.querySelectorAll('.timeline-slot');
    slots.forEach((slot, index) => {
        slot.style.opacity = '0';
        slot.style.transform = 'translateY(20px)';
        setTimeout(() => {
            slot.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
            slot.style.opacity = '1';
            slot.style.transform = 'translateY(0)';
        }, index * 50);
    });
}
```

**Fonctionnalités** :
- ✅ Génération dynamique du HTML pour chaque créneau (8h-17h)
- ✅ Filtrage des rendez-vous par statut
- ✅ Tri par priorité (payed > confirmed > completed)
- ✅ Indicateurs visuels (actif, en retard, libre)
- ✅ Animations d'apparition progressive
- ✅ Scroll automatique vers l'heure actuelle

### 2. Backend : Contrôleur (`TimelineController.php`)

#### Endpoint API

```php
// app/Http/Controllers/Medecin/TimelineController.php
public function getTimelineData()
{
    $user = Auth::user();
    $medecin = $user->isMedecin();
    
    if (!$medecin) {
        return response()->json(['error' => 'Accès non autorisé'], 403);
    }
    
    $today = Carbon::today();
    $now = Carbon::now();
    
    // Récupération des rendez-vous du jour
    $rendezVous = RendezVous::with(['patient'])
        ->where('medecin_id', $user->id)
        ->whereDate('date_debut', $today)
        ->whereNotIn('statut', ['pending', 'cancelled', 'rejected'])
        ->orderBy('date_debut')
        ->get();
    
    // Construction du timeline (8h-17h)
    $timeline = [];
    for ($hour = 8; $hour <= 17; $hour++) {
        $timeSlot = sprintf('%02d:00', $hour);
        
        // Filtrage des rendez-vous pour ce créneau
        $rdvInSlot = $rendezVous->filter(function ($rdv) use ($hour) {
            $rdvHour = Carbon::parse($rdv->date_debut)->hour;
            return $rdvHour === $hour;
        });
        
        $timeline[] = [
            'time' => $timeSlot,
            'hour' => $hour,
            'is_current' => $now->hour === $hour,
            'is_past' => $now->hour > $hour,
            'is_future' => $now->hour < $hour,
            'rendez_vous' => $rdvInSlot->map(function ($rdv) use ($now) {
                $startTime = Carbon::parse($rdv->date_debut);
                $endTime = Carbon::parse($rdv->date_fin ?? $startTime->copy()->addMinutes(30));
                
                return [
                    'id' => $rdv->id,
                    'patient_name' => $rdv->patient->prenom . ' ' . $rdv->patient->nom,
                    'patient_photo' => $rdv->patient->profile_photo_path
                        ? asset('storage/' . $rdv->patient->profile_photo_path)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($rdv->patient->prenom . ' ' . $rdv->patient->nom),
                    'start_time' => $startTime->format('H:i'),
                    'end_time' => $endTime->format('H:i'),
                    'type' => $rdv->type ?? 'consultation',
                    'statut' => $rdv->statut,
                    'is_active' => $now->between($startTime, $endTime),
                    'is_late' => $now->gt($endTime) && !in_array($rdv->statut, ['completed'], true),
                    'has_consultation_link' => !empty($rdv->lien_en_ligne),
                    'lien_en_ligne' => $rdv->lien_en_ligne
                ];
            })->values()
        ];
    }
    
    // Prochain rendez-vous
    $nextRendezVous = $rendezVous->filter(function ($rdv) use ($now) {
        return Carbon::parse($rdv->date_debut)->gt($now);
    })->first();
    
    // Rendez-vous actuel
    $currentRendezVous = $rendezVous->filter(function ($rdv) use ($now) {
        $startTime = Carbon::parse($rdv->date_debut);
        $endTime = Carbon::parse($rdv->date_fin ?? $startTime->copy()->addMinutes(30));
        return $now->between($startTime, $endTime);
    })->first();
    
    return response()->json([
        'timeline' => $timeline,
        'current_time' => $now->format('H:i'),
        'current_date' => $today->format('d M'),
        'current_hour' => $now->hour,
        'next_rendez_vous' => $nextRendezVous ? [...] : null,
        'current_rendez_vous' => $currentRendezVous ? [...] : null,
        'total_rendez_vous' => $rendezVous->count(),
        'completed_rendez_vous' => $rendezVous->where('statut', 'completed')->count(),
        'pending_rendez_vous' => $rendezVous->where('statut', 'pending')->count()
    ]);
}
```

**Fonctionnalités** :
- ✅ Authentification et autorisation (vérification rôle médecin)
- ✅ Récupération des rendez-vous du jour uniquement
- ✅ Filtrage des statuts exclus (pending, cancelled, rejected)
- ✅ Construction du timeline pour chaque heure (8h-17h)
- ✅ Calcul des états (actif, en retard, passé, futur)
- ✅ Informations sur le prochain rendez-vous
- ✅ Informations sur le rendez-vous actuel
- ✅ Statistiques (total, terminés, en attente)

### 3. Route API

```php
// routes/api.php
Route::middleware(['web', 'auth', 'role:medecin'])->prefix('medecin')->group(function () {
    Route::get('/timeline', [TimelineController::class, 'getTimelineData'])
        ->name('api.medecin.timeline.data');
});
```

**Sécurité** :
- ✅ Middleware `auth` : Utilisateur doit être connecté
- ✅ Middleware `role:medecin` : Seuls les médecins peuvent accéder
- ✅ Protection CSRF via middleware `web`

---

## Flux de Rafraîchissement

### 1. Initialisation (Chargement de la Page)

```
Page Load → DOMContentLoaded → initNavbarTimeline()
    ↓
window.loadTimeline() (premier appel)
    ↓
fetch('/api/medecin/timeline')
    ↓
updateTimeline(data)
    ↓
Affichage de la timeline
```

### 2. Rafraîchissement Automatique

```
setInterval(window.loadTimeline, 60000)
    ↓
Toutes les 60 secondes
    ↓
fetch('/api/medecin/timeline')
    ↓
updateTimeline(data)
    ↓
Mise à jour du DOM (remplacement complet)
```

### 3. Gestion des Erreurs

```
Erreur HTTP/Network
    ↓
showTimelineError(errorMessage)
    ↓
Affichage d'un message d'erreur avec bouton "Réessayer"
    ↓
Bouton "Réessayer" → window.loadTimeline()
```

---

## Détails Techniques

### Intervalle de Rafraîchissement

**Valeur** : **60 000 millisecondes** (1 minute)

```javascript
setInterval(window.loadTimeline, 60000);
```

**Pourquoi 60 secondes ?**
- ✅ Équilibre entre actualité des données et charge serveur
- ✅ Suffisant pour détecter les changements de statut
- ✅ Pas trop fréquent pour éviter la surcharge

**Modification possible** :
```javascript
// Rafraîchissement toutes les 30 secondes
setInterval(window.loadTimeline, 30000);

// Rafraîchissement toutes les 2 minutes
setInterval(window.loadTimeline, 120000);
```

### Filtrage des Rendez-Vous

**Statuts exclus** :
- `pending` : En attente (pas encore confirmé)
- `cancelled` : Annulé
- `rejected` : Rejeté

**Statuts inclus** :
- `confirmed` / `confirmé` : Confirmé
- `payed` : Payé
- `completed` : Terminé

**Logique** :
```php
->whereNotIn('statut', ['pending', 'cancelled', 'rejected'])
```

### Calcul des États

#### Rendez-vous Actif
```php
'is_active' => $now->between($startTime, $endTime)
```
- ✅ L'heure actuelle est entre le début et la fin du rendez-vous

#### Rendez-vous en Retard
```php
'is_late' => $now->gt($endTime) && !in_array($rdv->statut, ['completed'], true)
```
- ✅ L'heure actuelle est après la fin du rendez-vous
- ✅ Le rendez-vous n'est pas encore marqué comme terminé

#### Créneau Passé/Futur
```javascript
const isPast = hour < data.current_hour;
const isFuture = hour > data.current_hour;
```

### Priorité des Rendez-Vous

Si plusieurs rendez-vous dans le même créneau horaire :

```javascript
const priorityOrder = { 
    'payed': 0,        // Priorité la plus haute
    'confirmed': 1,    // Priorité moyenne
    'confirmé': 1,     // Priorité moyenne
    'completed': 2      // Priorité basse
};
```

**Logique** : Le rendez-vous avec la priorité la plus basse (0 = payed) est affiché.

---

## Fonctionnalités Avancées

### 1. Scroll Automatique

```javascript
function scrollToCurrentHour(currentHour) {
    const timelineContent = document.getElementById('timeline-content');
    const currentHourElement = timelineContent.querySelector(`[data-hour="${currentHour}"]`);
    if (currentHourElement) {
        const containerWidth = timelineContent.clientWidth;
        const elementLeft = currentHourElement.offsetLeft;
        const elementWidth = currentHourElement.offsetWidth;
        const scrollPosition = elementLeft - (containerWidth / 2) + (elementWidth / 2);
        timelineContent.scrollTo({ left: scrollPosition, behavior: 'smooth' });
    }
}
```

**Fonctionnalité** : Scroll automatique vers l'heure actuelle lors du chargement.

### 2. Animations d'Apparition

```javascript
slots.forEach((slot, index) => {
    slot.style.opacity = '0';
    slot.style.transform = 'translateY(20px)';
    setTimeout(() => {
        slot.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
        slot.style.opacity = '1';
        slot.style.transform = 'translateY(0)';
    }, index * 50); // Délai progressif
});
```

**Fonctionnalité** : Animation d'apparition progressive avec délai de 50ms entre chaque créneau.

### 3. Gestion des Liens de Consultation

```javascript
window.isConsultationLinkActive = function(startTime, endTime) {
    const now = new Date();
    const today = now.toISOString().split('T')[0];
    const startDateTime = new Date(`${today}T${startTime}`);
    const endDateTime = new Date(`${today}T${endTime}`);
    const fiveMinutesBefore = new Date(startDateTime.getTime() - 5 * 60 * 1000);
    return now >= fiveMinutesBefore && now <= endDateTime;
};
```

**Fonctionnalité** : Le lien de consultation est actif 5 minutes avant le début jusqu'à la fin.

### 4. Tooltips Interactifs

```javascript
chip.addEventListener('mouseenter', () => {
    showTooltip(computeSimpleTooltip());
});
chip.addEventListener('mouseleave', hideTooltip);
```

**Fonctionnalité** : Affichage d'informations détaillées au survol.

---

## Optimisations Possibles

### 1. Rafraîchissement Conditionnel

Au lieu de rafraîchir toutes les 60 secondes, rafraîchir uniquement si :
- Un rendez-vous approche (dans les 5 prochaines minutes)
- Un rendez-vous est en cours
- Un changement de statut a été détecté

```javascript
function shouldRefresh() {
    const now = new Date();
    const currentHour = now.getHours();
    
    // Rafraîchir plus souvent pendant les heures de travail
    if (currentHour >= 8 && currentHour <= 17) {
        return true;
    }
    return false;
}

setInterval(() => {
    if (shouldRefresh()) {
        window.loadTimeline();
    }
}, 60000);
```

### 2. Mise à Jour Incrémentale

Au lieu de remplacer tout le DOM, mettre à jour uniquement les créneaux modifiés :

```javascript
function updateTimelineIncremental(newData, oldData) {
    // Comparer les données
    // Mettre à jour uniquement les créneaux modifiés
}
```

### 3. Cache côté Client

Mettre en cache les données et ne rafraîchir que si nécessaire :

```javascript
let cachedTimeline = null;
let lastUpdate = null;

function loadTimeline() {
    const now = Date.now();
    if (cachedTimeline && (now - lastUpdate) < 30000) {
        // Utiliser le cache si moins de 30 secondes
        return;
    }
    // Sinon, faire l'appel API
}
```

---

## Dépannage

### Problème : Timeline ne se rafraîchit pas

**Vérifications** :
1. ✅ Vérifier que `navbar-timeline.js` est importé dans `app.js`
2. ✅ Vérifier la console JavaScript pour les erreurs
3. ✅ Vérifier que l'API `/api/medecin/timeline` répond correctement
4. ✅ Vérifier que l'utilisateur est bien connecté en tant que médecin

### Problème : Timeline vide

**Vérifications** :
1. ✅ Vérifier qu'il y a des rendez-vous pour aujourd'hui
2. ✅ Vérifier que les rendez-vous ne sont pas dans les statuts exclus
3. ✅ Vérifier les logs serveur (`Log::info` dans `TimelineController`)

### Problème : Erreur 403 (Accès non autorisé)

**Vérifications** :
1. ✅ Vérifier que l'utilisateur a le rôle `medecin`
2. ✅ Vérifier que le middleware `role:medecin` est bien appliqué

---

## Conclusion

Le système de rafraîchissement de la timeline dans la navbar du médecin fonctionne de manière **automatique et périodique** :

- ✅ **Chargement initial** : Au chargement de la page
- ✅ **Rafraîchissement automatique** : Toutes les 60 secondes
- ✅ **Mise à jour complète** : Remplacement du DOM à chaque rafraîchissement
- ✅ **Gestion des erreurs** : Affichage d'un message avec possibilité de réessayer
- ✅ **Optimisations visuelles** : Animations, scroll automatique, tooltips

**Avantages** :
- ✅ Données toujours à jour
- ✅ Détection automatique des changements de statut
- ✅ Expérience utilisateur fluide

**Points d'amélioration possibles** :
- 🔄 Rafraîchissement conditionnel (seulement si nécessaire)
- 🔄 Mise à jour incrémentale (seulement les créneaux modifiés)
- 🔄 Cache côté client pour réduire les appels API

---

**Documentation générée le** : {{ date('Y-m-d') }}
**Version** : 1.0.0


