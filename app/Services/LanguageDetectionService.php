<?php

namespace App\Services;

/**
 * Service de détection de langue pour le chatbot
 *
 * Détecte la langue du message utilisateur (français, anglais, arabe)
 * et fournit les instructions appropriées pour le modèle AI
 */
class LanguageDetectionService
{
    /**
     * Détecte la langue du message
     *
     * @param string $message Message de l'utilisateur
     * @return string Code de langue: 'fr', 'en', 'ar'
     */
    public function detectLanguage(string $message): string
    {
        // Nettoyer le message
        $message = trim($message);

        // Détecter l'arabe en premier (caractères uniques)
        if (preg_match('/[\x{0600}-\x{06FF}]/u', $message)) {
            return 'ar';
        }

        // Mots-clés français courants (plus complets)
        $frenchKeywords = [
            // Salutations
            'bonjour', 'salut', 'bonsoir', 'bonne', 'merci', 'au revoir',
            // Questions
            'avez-vous', 'comment', 'pouvez-vous', 'quand', 'où', 'pourquoi', 'combien',
            // Médical
            'mal', 'douleur', 'tête', 'ventre', 'fièvre', 'fatigué', 'médecin',
            'docteur', 'santé', 'symptôme', 'traitement', 'consulter', 'exercice',
            'physique', 'semaine', 'conseillé', 'adulte', 'recommandation', 'recommande',
            // Autres mots français courants
            'est', 'sont', 'être', 'avoir', 'faire', 'aller', 'venir',
            'de', 'des', 'les', 'le', 'la', 'un', 'une', 'du', 'dans', 'pour', 'par'
        ];

        // Mots-clés anglais courants
        $englishKeywords = [
            'hello', 'hi', 'thank', 'have', 'do you', 'can you', 'how', 'what', 'when', 'where',
            'pain', 'headache', 'stomach', 'fever', 'tired', 'doctor', 'health',
            'symptom', 'treatment', 'consult', 'exercise', 'physical', 'week', 'adult',
            'recommend', 'recommendation', 'advice', 'should', 'would', 'could'
        ];

        // Compter les occurrences
        $messageLower = mb_strtolower($message);
        $frenchCount = 0;
        $englishCount = 0;

        foreach ($frenchKeywords as $keyword) {
            if (mb_stripos($messageLower, $keyword) !== false) {
                $frenchCount++;
            }
        }

        foreach ($englishKeywords as $keyword) {
            if (mb_stripos($messageLower, $keyword) !== false) {
                $englishCount++;
            }
        }

        // Si aucun mot-clé trouvé, analyser les caractères et la structure
        if ($frenchCount === 0 && $englishCount === 0) {
            // Chercher des caractères spéciaux français
            if (preg_match('/[àâäéèêëïîôùûüÿç]/i', $message)) {
                return 'fr';
            }

            // Vérifier la structure typique du français (articles, prépositions)
            // Les mots français comme "de", "des", "les", "pour", "par" sont souvent présents
            if (preg_match('/\b(de|des|les|le|la|un|une|du|dans|pour|par|est|sont)\b/i', $message)) {
                return 'fr';
            }

            // Par défaut, considérer comme français (la langue principale de l'app)
            return 'fr';
        }

        // Retourner la langue avec le plus de correspondances
        if ($englishCount > $frenchCount) {
            return 'en';
        }

        return 'fr';
    }

    /**
     * Retourne le nom de la langue en français
     *
     * @param string $langCode Code de langue
     * @return string Nom de la langue
     */
    public function getLanguageName(string $langCode): string
    {
        return match($langCode) {
            'ar' => 'arabe',
            'en' => 'anglais',
            'fr' => 'français',
            default => 'français'
        };
    }

    /**
     * Retourne l'instruction de langue pour le prompt
     *
     * @param string $langCode Code de langue
     * @return string Instruction de langue
     */
    public function getLanguageInstruction(string $langCode): string
    {
        return match($langCode) {
            'ar' => 'IMPORTANT: Réponds UNIQUEMENT en arabe. Utilise l\'écriture arabe (من اليمين إلى اليسار).',
            'en' => 'IMPORTANT: Answer ONLY in English. Use English language.',
            'fr' => 'IMPORTANT: Réponds UNIQUEMENT en français. Utilise la langue française.',
            default => 'IMPORTANT: Réponds UNIQUEMENT en français. Utilise la langue française.'
        };
    }

    /**
     * Construit le prompt système multilingue
     *
     * @param string $langCode Code de langue
     * @return string Prompt système adapté à la langue
     */
    public function buildSystemPromptForLanguage(string $langCode): string
    {
        $languageInstruction = $this->getLanguageInstruction($langCode);

        if ($langCode === 'ar') {
            return "أنت مساعد طبي افتراضي متعاطف ومهني. {$languageInstruction}

تنسيق المحادثة:
- استخدم \"المريض:\" لأسئلة المريض
- استخدم \"المساعد:\" لإجاباتك
- كن طبيعيًا ومحاورًا

القواعد المطلقة:
- لا تضع أبدًا تشخيصًا طبيًا
- لا تنصح أبدًا بأدوية محددة (أسماء الأدوية، الجرعات)
- تقدم فقط نصائح عامة ومعلومات تعليمية
- كن دائمًا متعاطفًا ومطمئنًا ومهنيًا
- شجع دائمًا على استشارة متخصص صحي

أسلوبك:
- أجب بطريقة طبيعية ومحاورة
- تكيف إجابتك مع محتوى السؤال المحدد
- كن دقيقًا وذو صلة، تجنب الإجابات العامة المتكررة
- استخدم لغة سهلة ومفهومة

IMPORTANT: كل إجابة يجب أن تنتهي بـ:
\"⚠️ لا أستبدل الطبيب. للحصول على رأي شخصي، استشر متخصصًا صحيًا.\"";
        }

        if ($langCode === 'en') {
            return "You are an empathetic and professional virtual medical assistant. {$languageInstruction}

CONVERSATION FORMAT:
- Use \"Patient:\" for patient questions
- Use \"Assistant:\" for your answers
- Be natural and conversational

ABSOLUTE RULES:
- You NEVER make a medical diagnosis
- You NEVER recommend specific medications (drug names, dosages)
- You only provide general advice and educational information
- You are always empathetic, reassuring, and professional
- You systematically encourage consulting a healthcare professional

YOUR STYLE:
- Respond naturally and conversationally
- Adapt your answer to the specific content of the question asked
- Be precise and relevant, avoid repeated generic responses
- Use accessible and understandable language
- Personalize your responses according to the context of the question

IMPORTANT: Each response must end with:
\"⚠️ I do not replace a doctor. For personalized advice, consult a healthcare professional.\"";
        }

        // Français (par défaut)
        return "Tu es un assistant médical virtuel empathique et professionnel.

⚠️⚠️⚠️ RÈGLE CRITIQUE DE LANGUE : TU DOIS RÉPONDRE UNIQUEMENT EN FRANÇAIS. JAMAIS EN ANGLAIS.
- Si une question est en français, réponds en français
- Si une question contient des mots anglais ou des organisations anglaises, traduis-les en français
- Exemples de traductions obligatoires :
  * \"American Heart Association\" → \"Association Américaine de Cardiologie\"
  * \"minutes\" → \"minutes\" (garder tel quel, mais tout le reste en français)
  * \"150 minutes\" → \"150 minutes\" (les chiffres ne changent pas)
- TOUTE ta réponse doit être EN FRANÇAIS, de la première lettre à la dernière

FORMAT DE CONVERSATION :
- Sois naturel et conversationnel
- Réponds directement dans la même langue que la question

RÈGLES ABSOLUES :
- Tu ne poses JAMAIS de diagnostic médical
- Tu ne recommandes JAMAIS de médicaments spécifiques (noms de médicaments, dosages)
- Tu fournis uniquement des conseils généraux et des informations éducatives
- Tu es toujours empathique, rassurant et professionnel
- Tu encourages systématiquement la consultation d'un professionnel de santé

TON STYLE :
- Réponds de manière naturelle et conversationnelle EN FRANÇAIS
- Adapte ta réponse au contenu spécifique de la question posée
- Sois précis et pertinent, évite les réponses génériques répétées
- Utilise un langage accessible et compréhensible EN FRANÇAIS

IMPORTANT : Chaque réponse doit se terminer par :
\"⚠️ Je ne remplace pas un médecin. Pour un avis personnalisé, consultez un professionnel de santé.\"

RAPPEL ULTIME : TOUTES TES RÉPONSES EN FRANÇAIS, SANS EXCEPTION.";
    }

    /**
     * Retourne l'avertissement médical dans la langue appropriée
     *
     * @param string $langCode Code de langue
     * @return string Avertissement médical
     */
    public function getMedicalWarning(string $langCode): string
    {
        return match($langCode) {
            'ar' => "\n\n⚠️ لا أستبدل الطبيب. للحصول على رأي شخصي، استشر متخصصًا صحيًا.",
            'en' => "\n\n⚠️ I do not replace a doctor. For personalized advice, consult a healthcare professional.",
            'fr' => "\n\n⚠️ Je ne remplace pas un médecin. Pour un avis personnalisé, consultez un professionnel de santé.",
            default => "\n\n⚠️ Je ne remplace pas un médecin. Pour un avis personnalisé, consultez un professionnel de santé."
        };
    }
}

