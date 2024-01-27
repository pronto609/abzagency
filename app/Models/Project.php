<?php

namespace App\Models;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use HasFactory;

//    protected $table = 'projects';

    protected $fillable = [
        'title'
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, Member::class);
    }

    protected static function booted(): void
    {
        parent::boot();

        static::addGlobalScope('members', function (Builder $builder) {
            $user = Auth::user();
            if ($user) {
                $builder->whereHas('members', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                });
            }
        });
    }
}
