<?php

namespace App\Services;

use App\Models\RendezVous;
use Carbon\Carbon;

class GoogleMeetService
{
    /**
     * Crée un lien Google Meet via l'API Google Calendar si disponible et configurée.
     * Retourne null si non disponible.
     */
    public function createMeetLink(RendezVous $rendezVous): ?string
    {
        $clientClass = '\\Google\\Client';
        $calendarClass = '\\Google\\Service\\Calendar';
        $eventClass = '\\Google\\Service\\Calendar\\Event';
        $eventDateTimeClass = '\\Google\\Service\\Calendar\\EventDateTime';

        if (!class_exists($clientClass) || !class_exists($calendarClass)) {
            return null;
        }

        try {
            $client = new $clientClass();
            $client->setApplicationName(config('app.name', 'Laravel'));
            // Scope Calendar
            $client->setScopes([constant($calendarClass . '::CALENDAR')]);
            $credentials = env('GOOGLE_APPLICATION_CREDENTIALS', '');
            if (!$credentials) {
                return null;
            }
            $client->setAuthConfig(base_path($credentials));
            if (env('GOOGLE_CALENDAR_IMPERSONATE')) {
                $client->setSubject(env('GOOGLE_CALENDAR_IMPERSONATE'));
            }

            $service = new $calendarClass($client);

            $calendarId = env('GOOGLE_CALENDAR_ID', 'primary');
            $nowTz = new \DateTimeZone(env('APP_TIMEZONE', 'UTC'));

            $start = new $eventDateTimeClass();
            $start->setDateTime(Carbon::parse($rendezVous->date_debut)->setTimezone($nowTz)->toRfc3339String());

            $end = new $eventDateTimeClass();
            $end->setDateTime(Carbon::parse($rendezVous->date_fin ?? Carbon::parse($rendezVous->date_debut)->addMinutes(30))->setTimezone($nowTz)->toRfc3339String());

            $event = new $eventClass([
                'summary' => $rendezVous->titre ?: 'Consultation médicale',
                'description' => $rendezVous->description ?? '',
                'start' => $start,
                'end' => $end,
                'conferenceData' => [
                    'createRequest' => [
                        'requestId' => 'rdv-' . $rendezVous->id . '-' . uniqid(),
                        'conferenceSolutionKey' => ['type' => 'hangoutsMeet']
                    ]
                ]
            ]);

            $created = $service->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);

            if ($created && method_exists($created, 'getHangoutLink') && $created->getHangoutLink()) {
                return $created->getHangoutLink();
            }

            // Fallback: rechercher dans ConferenceData
            if (method_exists($created, 'getConferenceData')) {
                $conferenceData = $created->getConferenceData();
                if ($conferenceData && method_exists($conferenceData, 'getEntryPoints')) {
                    foreach ((array) $conferenceData->getEntryPoints() as $entry) {
                        if (method_exists($entry, 'getEntryPointType') && method_exists($entry, 'getUri')) {
                            if ($entry->getEntryPointType() === 'video' && $entry->getUri()) {
                                return $entry->getUri();
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            return null;
        }

        return null;
    }
}
