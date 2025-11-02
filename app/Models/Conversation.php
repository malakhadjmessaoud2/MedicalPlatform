<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $table = 'chatbot_conversations';

    protected $fillable = [
        'user_id',
        'title',
    ];

    /**
     * Relation avec l'utilisateur (patient)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les messages
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }

    /**
     * Récupère le dernier message de la conversation
     */
    public function lastMessage()
    {
        return $this->messages()->latest()->first();
    }
}
