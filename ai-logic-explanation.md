# 🤖 Logique de Liaison Modèle IA ↔ Champs Consultation

## 📊 **Flux de Données Complet**

### **1. 🔗 Liaison Modèle ↔ Champs Consultation**

```php
// 1. RÉCUPÉRATION DES DONNÉES (Contrôleur)
private function prepareConsultationData($consultation)
{
    return [
        // Champs de base
        'type' => $consultation->type,
        'date' => $consultation->date,
        'motif' => $consultation->motif,
        'symptomes' => $consultation->symptomes,
        
        // Signes vitaux
        'tension_arterielle' => $consultation->tension_arterielle,
        'temperature' => $consultation->temperature,
        'frequence_cardiaque' => $consultation->frequence_cardiaque,
        'saturation_o2' => $consultation->saturation_o2,
        
        // Examen et diagnostic
        'examen_physique' => $consultation->examen_physique,
        'diagnostic_presume' => $consultation->diagnostic_presume,
        
        // Traitement et suivi
        'medicaments_prescrits' => $consultation->medicaments_prescrits,
        'propositions_suivi' => $consultation->propositions_suivi,
        'gravite' => $consultation->gravite,
        
        // Relations
        'patient_name' => $consultation->rendezVous->patient->prenom . ' ' . $consultation->rendezVous->patient->nom,
        'medecin_name' => $consultation->rendezVous->medecin->prenom . ' ' . $consultation->rendezVous->medecin->nom
    ];
}
```

### **2. 🧠 Analyse et Filtrage des Données**

```php
// 2. FILTRAGE INTELLIGENT (Service IA)
private function getFilledFields(array $data): string
{
    $fields = [];
    
    // Champs de base (toujours affichés si présents)
    if (!empty($data['patient_name'])) {
        $fields[] = "- Patient: {$data['patient_name']}";
    }
    if (!empty($data['medecin_name'])) {
        $fields[] = "- Médecin: {$data['medecin_name']}";
    }
    
    // Champs médicaux (seulement si remplis)
    if (!empty($data['motif'])) {
        $fields[] = "- Motif: {$data['motif']}";
    }
    if (!empty($data['symptomes'])) {
        $fields[] = "- Symptômes: {$data['symptomes']}";
    }
    
    // Signes vitaux (groupés intelligemment)
    $vitals = [];
    if (!empty($data['tension_arterielle'])) {
        $vitals[] = "TA: {$data['tension_arterielle']}";
    }
    if (!empty($data['temperature'])) {
        $vitals[] = "Température: {$data['temperature']}";
    }
    if (!empty($vitals)) {
        $fields[] = "- Signes vitaux: " . implode(', ', $vitals);
    }
    
    return implode("\n", $fields);
}
```

### **3. 🎯 Construction du Prompt IA**

```php
// 3. PROMPT STRUCTURÉ POUR L'IA
private function buildTeleconsultationPrompt(array $data): string
{
    $schema = $this->jsonSchemaString();
    $filledFields = $this->getFilledFields($data);
    
    return "
Tu es un assistant IA médical pour médecine de famille. 
Réponds STRICTEMENT en JSON valide conforme au schéma ci-dessous.

DONNÉES CONSULTATION (champs remplis uniquement):
{$filledFields}

SCHÉMA JSON:
{$schema}
";
}
```

### **4. 📋 Schéma JSON pour l'IA**

```php
// 4. STRUCTURE ATTENDUE PAR L'IA
private function jsonSchemaString(): string
{
    return json_encode([
        'type' => 'object',
        'required' => ['consultation','diagnostic','treatment','recommendations','follow_up','summary','metadata'],
        'properties' => [
            'consultation' => [
                'type' => 'object',
                'properties' => [
                    'patient' => ['type' => ['string','null']],
                    'medecin' => ['type' => ['string','null']],
                    'date' => ['type' => ['string','null']],
                    'motif' => ['type' => ['string','null']],
                    'symptomes' => ['type' => ['string','null']],
                    'examen_physique' => ['type' => ['string','null']],
                    'signes_vitaux' => [
                        'type' => 'object',
                        'properties' => [
                            'tension_arterielle' => ['type' => ['string','null']],
                            'temperature_c' => ['type' => ['string','null','number']],
                            'frequence_cardiaque_bpm' => ['type' => ['string','null','number']],
                            'saturation_o2_pct' => ['type' => ['string','null','number']],
                        ]
                    ],
                ]
            ],
            'diagnostic' => [
                'type' => 'object',
                'properties' => [
                    'summary' => ['type' => ['string','null']],
                    'notes' => ['type' => ['string','null']],
                    'codes' => ['type' => 'array', 'items' => ['type' => 'string']]
                ]
            ],
            'treatment' => [
                'type' => 'object',
                'properties' => [
                    'plan' => ['type' => ['string','null']],
                    'medications' => ['type' => 'array', 'items' => ['type' => 'string']]
                ]
            ],
            'recommendations' => [
                'type' => 'object',
                'properties' => [
                    'hygiene' => ['type' => ['string','null']],
                    'follow_up' => ['type' => ['string','null']],
                    'specialist_referral' => ['type' => ['string','null']],
                    'medication_instructions' => ['type' => ['string','null']]
                ]
            ],
            'follow_up' => [
                'type' => 'object',
                'properties' => [
                    'plan' => ['type' => ['string','null']],
                    'monitoring_points' => ['type' => ['string','null']],
                    'warning_signs' => ['type' => ['string','null']],
                    'appointment_schedule' => ['type' => ['string','null']]
                ]
            ],
            'summary' => [
                'type' => 'object',
                'properties' => [
                    'patient_friendly' => ['type' => ['string','null']],
                    'key_points' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'next_steps' => ['type' => ['string','null']]
                ]
            ],
            'metadata' => [
                'type' => 'object',
                'properties' => [
                    'model' => ['type' => 'string'],
                    'generated_at' => ['type' => 'string']
                ]
            ]
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
```

### **5. 🔄 Normalisation des Réponses**

```php
// 5. TRAITEMENT DE LA RÉPONSE IA
private function normalizeSchema(array $modelOutput, array $ctx): array
{
    return [
        'consultation' => [
            'patient' => $ctx['patient_name'] ?? null,
            'medecin' => $ctx['medecin_name'] ?? null,
            'date' => $ctx['date'] ?? null,
            'type' => $ctx['type'] ?? null,
            'motif' => $ctx['motif'] ?? null,
            'symptomes' => $ctx['symptomes'] ?? null,
            'examen_physique' => $ctx['examen_physique'] ?? null,
            'signes_vitaux' => [
                'tension_arterielle' => $ctx['tension_arterielle'] ?? null,
                'temperature_c' => $ctx['temperature'] ?? null,
                'frequence_cardiaque_bpm' => $ctx['frequence_cardiaque'] ?? null,
                'saturation_o2_pct' => $ctx['saturation_o2'] ?? null,
            ],
        ],
        'diagnostic' => [
            'summary' => $modelOutput['diagnostic']['summary'] ?? null,
            'notes' => $modelOutput['diagnostic']['notes'] ?? null,
            'codes' => $modelOutput['diagnostic']['codes'] ?? [],
        ],
        'treatment' => [
            'plan' => $modelOutput['treatment']['plan'] ?? null,
            'medications' => $modelOutput['treatment']['medications'] ?? [],
        ],
        'recommendations' => [
            'hygiene' => $modelOutput['recommendations']['hygiene'] ?? null,
            'follow_up' => $modelOutput['recommendations']['follow_up'] ?? null,
            'specialist_referral' => $modelOutput['recommendations']['specialist_referral'] ?? null,
            'medication_instructions' => $modelOutput['recommendations']['medication_instructions'] ?? null,
        ],
        'follow_up' => [
            'plan' => $modelOutput['follow_up']['plan'] ?? null,
            'monitoring_points' => $modelOutput['follow_up']['monitoring_points'] ?? null,
            'warning_signs' => $modelOutput['follow_up']['warning_signs'] ?? null,
            'appointment_schedule' => $modelOutput['follow_up']['appointment_schedule'] ?? null,
        ],
        'summary' => [
            'patient_friendly' => $modelOutput['summary']['patient_friendly'] ?? null,
            'key_points' => $modelOutput['summary']['key_points'] ?? [],
            'next_steps' => $modelOutput['summary']['next_steps'] ?? null,
        ],
        'metadata' => [
            'model' => 'gemini-2.0-flash',
            'generated_at' => now()->toISOString(),
        ],
    ];
}
```

## 🎯 **Champs du Modèle IA Utilisés**

### **A. Champs d'Entrée (Consultation → IA)**
```php
// Champs analysés par l'IA
$inputFields = [
    'type', 'date', 'motif', 'symptomes',
    'tension_arterielle', 'temperature', 'frequence_cardiaque', 'saturation_o2',
    'examen_physique', 'diagnostic_presume',
    'medicaments_prescrits', 'propositions_suivi', 'gravite',
    'patient_name', 'medecin_name'
];
```

### **B. Champs de Sortie (IA → Base de Données)**
```php
// Champs générés par l'IA et sauvegardés
$outputFields = [
    // Compte-rendu principal
    'compte_rendu_ia' => $reportText,
    'teleconsultation_report_ia' => $reportText,
    
    // Diagnostic
    'diagnostic_summary_ia' => $doc['diagnostic']['summary'],
    
    // Traitement
    'treatment_plan_ia' => $doc['treatment']['plan'],
    
    // Recommandations
    'hygiene_instructions_ia' => $doc['recommendations']['hygiene'],
    'follow_up_plan_ia' => $doc['recommendations']['follow_up'],
    'specialist_referral_ia' => $doc['recommendations']['specialist_referral'],
    'medication_instructions_ia' => $doc['recommendations']['medication_instructions'],
    
    // Suivi
    'monitoring_points_ia' => $doc['follow_up']['monitoring_points'],
    'warning_signs_ia' => $doc['follow_up']['warning_signs'],
    'appointment_schedule_ia' => $doc['follow_up']['appointment_schedule'],
    
    // Résumé
    'online_summary_ia' => $doc['summary']['patient_friendly'],
    'key_points_ia' => $doc['summary']['key_points'],
    'next_steps_ia' => $doc['summary']['next_steps'],
    
    // Métadonnées
    'ai_service_used' => 'google-gemini-2.0-flash',
    'compte_rendu_generated_at' => now(),
];
```

## 🔄 **Flux Complet d'Analyse**

```
1. CONSULTATION (Base de données)
   ↓
2. prepareConsultationData() (Extraction)
   ↓
3. getFilledFields() (Filtrage intelligent)
   ↓
4. buildTeleconsultationPrompt() (Construction prompt)
   ↓
5. callGoogleGemini() (Appel API IA)
   ↓
6. normalizeSchema() (Normalisation réponse)
   ↓
7. buildCompleteTeleconsultationText() (Formatage final)
   ↓
8. SAUVEGARDE (Base de données)
```

## 🎯 **Points Clés de l'Analyse**

### **✅ Filtrage Intelligent**
- Seuls les champs remplis sont envoyés à l'IA
- Évite les prompts avec des données vides
- Optimise la consommation de tokens

### **✅ Structure JSON Rigide**
- Schéma JSON strict pour l'IA
- Format de réponse prévisible
- Facilite le parsing et la normalisation

### **✅ Relations Modèle**
- Liaison automatique avec `rendezVous.patient`
- Liaison automatique avec `rendezVous.medecin`
- Données contextuelles enrichies

### **✅ Persistance Complète**
- Sauvegarde dans tous les champs IA
- Métadonnées de traçabilité
- Timestamps de génération

Cette logique assure une **liaison parfaite** entre le modèle de consultation et l'IA, avec une **analyse intelligente** des données ! 🚀
