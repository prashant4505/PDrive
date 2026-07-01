<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriveFile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'folder_id',
        'original_name',
        'stored_name',
        'disk',
        'path',
        'mime_type',
        'extension',
        'size',
        'is_favorite',
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
        'size' => 'integer',
    ];

    protected $appends = [
        'preview_url',
        'human_size',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    public function scopeOwnedBy(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function getPreviewUrlAttribute(): ?string
    {
        return route('files.content', $this);
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = max($this->size, 0);
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $bytes > 0 ? (int) floor(log($bytes, 1024)) : 0;
        $power = min($power, count($units) - 1);
        $value = $bytes / (1024 ** $power);

        return number_format($value, $power === 0 ? 0 : 1).' '.$units[$power];
    }

    public function isPreviewable(): bool
    {
        return $this->isImage() || $this->isVideo() || $this->isAudio() || $this->isPdf() || $this->isText();
    }

    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime_type, 'image/');
    }

    public function isVideo(): bool
    {
        return str_starts_with((string) $this->mime_type, 'video/');
    }

    public function isAudio(): bool
    {
        return str_starts_with((string) $this->mime_type, 'audio/');
    }

    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    public function isText(): bool
    {
        if (str_starts_with((string) $this->mime_type, 'text/')) {
            return true;
        }

        return in_array(strtolower((string) $this->extension), [
            'php', 'js', 'ts', 'tsx', 'jsx', 'json', 'md', 'txt', 'css', 'html', 'xml', 'yml', 'yaml', 'log',
        ], true);
    }
}
