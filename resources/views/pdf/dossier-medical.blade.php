<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dossier Médical - {{ $patient['prenom'] }} {{ $patient['nom'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #2E7D32;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            color: #2E7D32;
            font-size: 20px;
            margin: 0 0 8px 0;
            font-weight: bold;
        }

        .header .subtitle {
            color: #666;
            font-size: 12px;
            margin: 0;
        }

        .confidential-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 10px;
            color: #856404;
            font-weight: bold;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-section h2 {
            color: #2E7D32;
            font-size: 14px;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 3px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 35%;
            padding: 4px;
            background-color: #f8f9fa;
            font-size: 10px;
        }

        .info-value {
            display: table-cell;
            padding: 4px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }

        .consultation-section {
            margin-bottom: 25px;
        }

        .consultation {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 3px;
            overflow: hidden;
        }

        .consultation-header {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 11px;
        }

        .consultation-content {
            padding: 12px;
        }

        .consultation-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .consultation-row {
            display: table-row;
        }

        .consultation-label {
            display: table-cell;
            font-weight: bold;
            width: 30%;
            padding: 3px;
            font-size: 9px;
            background-color: #f8f9fa;
        }

        .consultation-value {
            display: table-cell;
            padding: 3px;
            font-size: 9px;
            border-bottom: 1px solid #eee;
        }

        .vital-signs {
            background-color: #f8f9fa;
            padding: 8px;
            margin: 8px 0;
            border-radius: 3px;
        }

        .vital-signs h4 {
            margin: 0 0 5px 0;
            font-size: 10px;
            color: #2E7D32;
        }

        .vital-signs-grid {
            display: table;
            width: 100%;
        }

        .vital-sign-item {
            display: table-cell;
            text-align: center;
            padding: 3px;
            font-size: 9px;
        }

        .vital-sign-value {
            font-weight: bold;
            color: #2E7D32;
        }

        .ordonnance {
            margin-top: 12px;
            padding: 10px;
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 3px;
        }

        .ordonnance h4 {
            color: #856404;
            margin: 0 0 8px 0;
            font-size: 11px;
            font-weight: bold;
            text-decoration: underline;
        }

        .medicaments {
            margin-bottom: 8px;
        }

        .medicament-item {
            padding: 2px 0;
            font-size: 9px;
            margin-left: 10px;
        }

        .ordonnance-notes {
            margin-top: 8px;
            padding: 5px;
            background-color: #fff;
            border-left: 3px solid #ffc107;
        }

        .ordonnance-notes strong {
            font-size: 9px;
            color: #856404;
        }

        .ordonnance-image {
            margin: 15px 0;
            text-align: center;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .ordonnance-image img {
            max-width: 100%;
            max-height: 600px;
            width: auto;
            height: auto;
            border: 2px solid #ffc107;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            object-fit: contain;
            page-break-inside: avoid;
        }

        .page-break {
            page-break-before: always;
        }

        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }

        .signature {
            margin-top: 15px;
            text-align: right;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 150px;
            display: inline-block;
            margin-top: 20px;
        }

        .signature-text {
            margin-top: 3px;
            font-size: 9px;
            color: #666;
        }

        .document-info {
            background-color: #f8f9fa;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 3px;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DOSSIER MÉDICAL CONFIDENTIEL</h1>
        <p class="subtitle">Historique médical et prescriptions</p>
    </div>


    <div class="document-info">
        <strong>Document généré le :</strong> {{ date('d/m/Y à H:i') }}<br>
        <strong>Patient :</strong> {{ $patient['prenom'] }} {{ $patient['nom'] }} |
        <strong>Médecin :</strong> Dr. {{ $medecin['prenom'] }} {{ $medecin['nom'] }}
    </div>

    <div class="info-section">
        <h2>Informations du patient</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nom complet :</div>
                <div class="info-value">{{ $patient['prenom'] }} {{ $patient['nom'] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email :</div>
                <div class="info-value">{{ $patient['email'] }}</div>
            </div>

        </div>
    </div>

    <div class="info-section">
        <h2>Médecin traitant</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nom :</div>
                <div class="info-value">Dr. {{ $medecin['prenom'] }} {{ $medecin['nom'] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Spécialité :</div>
                <div class="info-value">{{ $medecin['specialite'] }}</div>
            </div>
        </div>
    </div>

    <div class="consultation-section">
        <h2>Historique des consultations médicales</h2>

        @foreach($rendez_vous as $index => $rv)
            @if(count($rv['consultations']) > 0)
                @foreach($rv['consultations'] as $consultation)
                    <div class="consultation">
                        <div class="consultation-header">
                            Consultation du {{ $consultation['date_formatted'] ? explode(' à ', $consultation['date_formatted'])[0] : '—' }} - Rendez-vous du {{ $rv['date_debut_formatted'] ? explode(' à ', $rv['date_debut_formatted'])[0] : '—' }}
                        </div>
                        <div class="consultation-content">

                            <!-- Informations principales -->
                            <div class="consultation-grid">
                                @if($consultation['motif'])
                                <div class="consultation-row">
                                    <div class="consultation-label">Motif de consultation :</div>
                                    <div class="consultation-value">{{ $consultation['motif'] }}</div>
                                </div>
                                @endif

                                @if($consultation['symptomes'])
                                <div class="consultation-row">
                                    <div class="consultation-label">Symptômes :</div>
                                    <div class="consultation-value">{{ $consultation['symptomes'] }}</div>
                                </div>
                                @endif

                                @if($consultation['traitement_actuel'])
                                <div class="consultation-row">
                                    <div class="consultation-label">Traitement actuel :</div>
                                    <div class="consultation-value">{{ $consultation['traitement_actuel'] }}</div>
                                </div>
                                @endif

                                @if($consultation['medicaments_prescrits'])
                                <div class="consultation-row">
                                    <div class="consultation-label">Médicaments prescrits :</div>
                                    <div class="consultation-value">{{ $consultation['medicaments_prescrits'] }}</div>
                                </div>
                                @endif

                                @if($consultation['propositions_suivi'])
                                <div class="consultation-row">
                                    <div class="consultation-label">Propositions de suivi :</div>
                                    <div class="consultation-value">{{ $consultation['propositions_suivi'] }}</div>
                                </div>
                                @endif

                                @if($consultation['instructions_particulieres'])
                                <div class="consultation-row">
                                    <div class="consultation-label">Instructions particulières :</div>
                                    <div class="consultation-value">{{ $consultation['instructions_particulieres'] }}</div>
                                </div>
                                @endif

                                @if($consultation['evolution_symptomes'])
                                <div class="consultation-row">
                                    <div class="consultation-label">Évolution des symptômes :</div>
                                    <div class="consultation-value">{{ $consultation['evolution_symptomes'] }}</div>
                                </div>
                                @endif

                                @if($consultation['effets_secondaires'])
                                <div class="consultation-row">
                                    <div class="consultation-label">Effets secondaires :</div>
                                    <div class="consultation-value">{{ $consultation['effets_secondaires'] }}</div>
                                </div>
                                @endif

                                @if($consultation['examens_controle'])
                                <div class="consultation-row">
                                    <div class="consultation-label">Examens de contrôle :</div>
                                    <div class="consultation-value">{{ $consultation['examens_controle'] }}</div>
                                </div>
                                @endif

                                @if($consultation['orientation_patient'])
                                <div class="consultation-row">
                                    <div class="consultation-label">Orientation :</div>
                                    <div class="consultation-value">{{ $consultation['orientation_patient'] }}</div>
                                </div>
                                @endif
                            </div>

                            <!-- Signes vitaux -->
                            @if($consultation['tension_arterielle'] || $consultation['frequence_cardiaque'] || $consultation['temperature'] || $consultation['saturation_o2'] || $consultation['poids'] || $consultation['taille'] || $consultation['imc'])
                            <div class="vital-signs">
                                <h4>Signes vitaux et mesures anthropométriques</h4>
                                <div class="vital-signs-grid">
                                    @if($consultation['tension_arterielle'])
                                    <div class="vital-sign-item">
                                        <div class="vital-sign-value">{{ $consultation['tension_arterielle'] }}</div>
                                        <div>Tension artérielle</div>
                                    </div>
                                    @endif

                                    @if($consultation['frequence_cardiaque'])
                                    <div class="vital-sign-item">
                                        <div class="vital-sign-value">{{ $consultation['frequence_cardiaque'] }}</div>
                                        <div>Fréquence cardiaque</div>
                                    </div>
                                    @endif

                                    @if($consultation['temperature'])
                                    <div class="vital-sign-item">
                                        <div class="vital-sign-value">{{ $consultation['temperature'] }}°C</div>
                                        <div>Température</div>
                                    </div>
                                    @endif

                                    @if($consultation['saturation_o2'])
                                    <div class="vital-sign-item">
                                        <div class="vital-sign-value">{{ $consultation['saturation_o2'] }}%</div>
                                        <div>Saturation O₂</div>
                                    </div>
                                    @endif

                                    @if($consultation['poids'])
                                    <div class="vital-sign-item">
                                        <div class="vital-sign-value">{{ $consultation['poids'] }} kg</div>
                                        <div>Poids</div>
                                    </div>
                                    @endif

                                    @if($consultation['taille'])
                                    <div class="vital-sign-item">
                                        <div class="vital-sign-value">{{ $consultation['taille'] }} cm</div>
                                        <div>Taille</div>
                                    </div>
                                    @endif

                                    @if($consultation['imc'])
                                    <div class="vital-sign-item">
                                        <div class="vital-sign-value">{{ $consultation['imc'] }}</div>
                                        <div>IMC</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Ordonnance -->
                            @if($consultation['ordonnance'])
                                <div class="ordonnance">
                                    <h4>ORDONNANCE MÉDICALE</h4>

                                                                        <!-- Affichage de l'image de l'ordonnance si disponible -->
                                    @if($consultation['ordonnance']['file_base64'])
                                        <div class="ordonnance-image" style="margin: 15px 0; text-align: center;">
                                            <img src="{{ $consultation['ordonnance']['file_base64'] }}"
                                                 alt="Ordonnance médicale"
                                                 style="max-width: 100%; max-height: 600px; width: auto; height: auto; border: 2px solid #ffc107; border-radius: 5px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); object-fit: contain;" />
                                        </div>
                                    @endif

                                    <!-- Affichage des informations textuelles de l'ordonnance -->
                                    @if($consultation['ordonnance']['medicaments'])
                                        <div class="medicaments">
                                            <strong>Médicaments prescrits :</strong>
                                            @if(is_array($consultation['ordonnance']['medicaments']))
                                                @foreach($consultation['ordonnance']['medicaments'] as $medicament)
                                                    <div class="medicament-item">• {{ $medicament }}</div>
                                                @endforeach
                                            @else
                                                <div class="medicament-item">{{ $consultation['ordonnance']['medicaments'] }}</div>
                                            @endif
                                        </div>
                                    @endif

                                    @if($consultation['ordonnance']['notes'])
                                        <div class="ordonnance-notes">
                                            <strong>Notes et instructions :</strong><br>
                                            {{ $consultation['ordonnance']['notes'] }}
                                        </div>
                                    @endif

                                   


                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        @endforeach

        @if($rendez_vous->where('consultations', [])->count() > 0)
            <div class="consultation">
                <div class="consultation-header">
                    Rendez-vous sans consultation
                </div>
                <div class="consultation-content">
                    <p style="color: #666; font-style: italic; font-size: 10px;">
                        Les rendez-vous suivants n'ont pas encore donné lieu à une consultation médicale :
                    </p>
                    @foreach($rendez_vous as $rv)
                        @if(count($rv['consultations']) == 0)
                            <div style="margin: 5px 0; font-size: 10px;">
                                • {{ $rv['date_debut'] }} - {{ $rv['type'] }} ({{ $rv['statut'] }})
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="footer">
        <p><strong>Document confidentiel</strong> - Ce dossier médical est strictement confidentiel et destiné uniquement au patient et aux professionnels de santé autorisés.</p>
        <p>La divulgation non autorisée de ces informations est interdite par la loi.</p>
    </div>

    <div class="signature">
        <div class="signature-line"></div>
        <div class="signature-text">Signature et cachet du médecin</div>
    </div>
</body>
</html>
