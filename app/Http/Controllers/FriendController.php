<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class FriendController extends Controller
{
    /**
     * Affiche la liste des amis et les demandes en attente.
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. Demandes reçues (Pending)
        $requestIds = DB::table('friendships')
            ->where('receiver_id', $userId)
            ->where('status', 'pending')
            ->pluck('sender_id');
        
        $requests = User::whereIn('id', $requestIds)->get();

        // 2. Amis confirmés (Accepted)
        $friendIds = DB::table('friendships')
            ->where(function($q) use ($userId) {
                $q->where('sender_id', $userId)->where('status', 'accepted');
            })
            ->orWhere(function($q) use ($userId) {
                $q->where('receiver_id', $userId)->where('status', 'accepted');
            })
            ->get()
            ->map(function($friendship) use ($userId) {
                return $friendship->sender_id == $userId ? $friendship->receiver_id : $friendship->sender_id;
            });
        
        $friends = User::whereIn('id', $friendIds)
            ->orderBy('current_xp', 'desc')
            ->get()
            ->map(function($user, $index) {
                return [
                    'id' => $user->id,
                    'rank' => $index + 1,
                    'name' => $user->username,
                    'points' => $user->current_xp,
                    'avatar_color' => 'bg-gray-300' // Tu pourras améliorer ça avec de vrais avatars
                ];
            });

        return view('friends', compact('friends', 'requests'));
    }

    /**
     * Affiche le formulaire de recherche d'amis.
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $results = [];

        if ($search) {
            $results = User::where('username', 'LIKE', "%{$search}%")
                ->where('id', '!=', Auth::id())
                ->get();
        }

        return view('friends_add', ['results' => $results, 'search' => $search]);
    }

    /**
     * Envoie une demande d'ami.
     */
    public function store($id)
    {
        $myId = Auth::id();
        
        // Vérifier doublon
        $exists = DB::table('friendships')
            ->where(function($q) use ($myId, $id) {
                $q->where('sender_id', $myId)->where('receiver_id', $id);
            })
            ->orWhere(function($q) use ($myId, $id) {
                $q->where('sender_id', $id)->where('receiver_id', $myId);
            })
            ->exists();

        if (!$exists) {
            DB::table('friendships')->insert([
                'sender_id' => $myId,
                'receiver_id' => $id,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return back()->with('success', 'Demande envoyée !');
        }

        return back()->with('info', 'Relation déjà existante ou en attente.');
    }

    /**
     * Accepte une demande d'ami.
     */
    public function accept($id)
    {
        $myId = Auth::id();
        
        DB::table('friendships')
            ->where('sender_id', $id)
            ->where('receiver_id', $myId)
            ->update(['status' => 'accepted']);

        return back()->with('success', 'Ami accepté !');
    }

    /**
     * Supprime un ami ou refuse une demande.
     */
    public function destroy($id)
    {
        $myId = Auth::id();
        
        DB::table('friendships')
            ->where(function($q) use ($myId, $id) {
                $q->where('sender_id', $myId)->where('receiver_id', $id);
            })
            ->orWhere(function($q) use ($myId, $id) {
                $q->where('sender_id', $id)->where('receiver_id', $myId);
            })
            ->delete();

        return back()->with('success', 'Relation supprimée.');
    }
}