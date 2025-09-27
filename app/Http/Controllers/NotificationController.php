<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Récupère les notifications de l'utilisateur connecté
     */
    public function index(Request $request)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }

            $perPage = $request->get('per_page', 10);
            $notifications = $user->notifications()
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'notifications' => $notifications->items(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des notifications: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des notifications'], 500);
        }
    }

    /**
     * Marque une notification comme lue
     */
    public function markAsRead(Request $request, $id)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }

            $notification = $user->notifications()->find($id);

            if (!$notification) {
                return response()->json(['error' => 'Notification non trouvée'], 404);
            }

            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marquée comme lue'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du marquage de la notification: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors du marquage de la notification'], 500);
        }
    }

    /**
     * Marque toutes les notifications comme lues
     */
    public function markAllAsRead(Request $request)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }

            $user->unreadNotifications->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Toutes les notifications ont été marquées comme lues'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du marquage de toutes les notifications: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors du marquage des notifications'], 500);
        }
    }

    /**
     * Supprime une notification
     */
    public function destroy(Request $request, $id)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }

            $notification = $user->notifications()->find($id);

            if (!$notification) {
                return response()->json(['error' => 'Notification non trouvée'], 404);
            }

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification supprimée'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la notification: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la suppression de la notification'], 500);
        }
    }

    /**
     * Récupère le nombre de notifications non lues
     */
    public function unreadCount(Request $request)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }

            $unreadCount = $user->unreadNotifications->count();

            return response()->json([
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du comptage des notifications non lues: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors du comptage des notifications'], 500);
        }
    }

    /**
     * Récupère les notifications récentes (pour le dropdown)
     */
    public function recent(Request $request)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }

            $limit = $request->get('limit', 5);
            $notifications = $user->notifications()
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $user->unreadNotifications->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des notifications récentes: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des notifications'], 500);
        }
    }
}
