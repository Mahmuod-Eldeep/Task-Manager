<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'is_Done',
        'project_id',
    ];

    protected $casts = [
        'is_Done' => 'boolean'
    ];



    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }


    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope('member', function (Builder $builder) {
            $builder->where('creator_id', Auth::id())
                ->orwhereIn('project_id', Auth::user()->memberships->pluck('id'));
        });
    }
}
