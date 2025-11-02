<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $table = 'chatbot_messages';

    protected $fillable = [
        'conversation_id',
        'content',
        'is_from_user',
    ];

    protected $casts = [
        'is_from_user' => 'boolean',
    ];

    /**
     * Relation avec la conversation
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }
}
