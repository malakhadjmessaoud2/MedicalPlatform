<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte-Rendu de Consultation</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2563eb;
            font-size: 24px;
            margin: 0;
        }

        .header .subtitle {
            color: #6b7280;
            font-size: 14px;
            margin-top: 5px;
        }

        .patient-info {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .patient-info h3 {
            color: #374151;
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
        }

        .info-label {
            font-weight: bold;
            color: #6b7280;
        }

        .info-value {
            color: #111827;
        }

        .section {
            margin-bottom: 25px;
        }

        .section h2 {
            color: #2563eb;
            font-size: 18px;
            margin: 0 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
        }

        .section h3 {
            color: #374151;
            font-size: 16px;
            margin: 15px 0 10px 0;
        }

        .content {
            background-color: #ffffff;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            white-space: pre-wrap;
            font-size: 14px;
            line-height: 1.7;
        }

        .vital-signs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 15px 0;
        }

        .vital-item {
            text-align: center;
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 6px;
        }

        .vital-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: bold;
            text-transform: uppercase;
        }

        .vital-value {
            font-size: 16px;
            color: #111827;
            font-weight: bold;
            margin-top: 5px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }

        .signature {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: end;
        }

        .signature-line {
            border-bottom: 1px solid #374151;
            width: 200px;
            height: 40px;
        }

        .signature-label {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }

        .ai-badge {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
            margin-left: 10px;
        }

        .recommendations {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 15px 0;
        }

        .recommendations h4 {
            color: #92400e;
            margin: 0 0 10px 0;
            font-size: 14px;
        }

        .recommendations p {
            margin: 0;
            color: #92400e;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>COMPTE-RENDU DE CONSULTATION MÉDICALE</h1>
        <div class="subtitle">
            Généré le {{ now()->format('d/m/Y à H:i') }}
            <span class="ai-badge">🤖 IA</span>
        </div>
    </div>

    <div class="patient-info">
        <h3>Informations Patient</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Nom:</span>
                <span class="info-value">{{ $consultation->rendezVous->patient->prenom }} {{ $consultation->rendezVous->patient->nom }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Date de naissance:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($consultation->rendezVous->patient->date_naissance)->format('d/m/Y') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Date consultation:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($consultation->date)->format('d/m/Y') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Type:</span>
                <span class="info-value">{{ ucfirst($consultation->type) }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Médecin:</span>
                <span class="info-value">Dr. {{ $consultation->rendezVous->medecin->prenom }} {{ $consultation->rendezVous->medecin->nom }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Spécialité:</span>
                <span class="info-value">{{ $consultation->rendezVous->medecin->specialite ?? 'Médecine générale' }}</span>
            </div>
        </div>
    </div>

    @if($consultation->tension_arterielle || $consultation->temperature || $consultation->frequence_cardiaque || $consultation->saturation_o2)
    <div class="section">
        <h2>Paramètres Vitaux</h2>
        <div class="vital-signs">
            @if($consultation->tension_arterielle)
            <div class="vital-item">
                <div class="vital-label">Tension Artérielle</div>
                <div class="vital-value">{{ $consultation->tension_arterielle }}</div>
            </div>
            @endif

            @if($consultation->temperature)
            <div class="vital-item">
                <div class="vital-label">Température</div>
                <div class="vital-value">{{ $consultation->temperature }}°C</div>
            </div>
            @endif

            @if($consultation->frequence_cardiaque)
            <div class="vital-item">
                <div class="vital-label">Fréquence Cardiaque</div>
                <div class="vital-value">{{ $consultation->frequence_cardiaque }} bpm</div>
            </div>
            @endif

            @if($consultation->saturation_o2)
            <div class="vital-item">
                <div class="vital-label">Saturation O2</div>
                <div class="vital-value">{{ $consultation->saturation_o2 }}%</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="section">
        <h2>Compte-Rendu de Consultation</h2>
        <div class="content">{{ $compte_rendu }}</div>
    </div>

    @if($resume)
    <div class="section">
        <h2>Résumé Exécutif</h2>
        <div class="content">{{ $resume }}</div>
    </div>
    @endif

    @if($recommandations)
    <div class="recommendations">
        <h4>Recommandations Spéciales</h4>
        <p>{{ $recommandations }}</p>
    </div>
    @endif

    <div class="signature">
        <div>
            <div class="signature-line"></div>
            <div class="signature-label">Signature du Médecin</div>
        </div>
        <div>
            <div class="signature-line"></div>
            <div class="signature-label">Date</div>
        </div>
    </div>

    <div class="footer">
        <p>Ce document a été généré automatiquement par un système d'intelligence artificielle.</p>
        <p>Il est recommandé de vérifier et valider le contenu avant utilisation clinique.</p>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }} - Système de Gestion Médicale</p>
    </div>
</body>
</html>
