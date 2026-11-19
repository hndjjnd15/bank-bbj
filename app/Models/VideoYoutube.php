<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VideoYoutube extends Model
{
    protected $fillable = ['url', 'urutan', 'diterbitkan'];

    protected $casts = [
        'urutan' => 'integer',
        'diterbitkan' => 'boolean',
    ];

    public function scopeTayang(Builder $query): Builder
    {
        return $query
            ->where('diterbitkan', true)
            ->orderBy('urutan')
            ->orderBy('id');
    }

    public function getEmbedUrlAttribute(): ?string
    {
        $id = self::idDariUrl($this->url);

        return $id ? 'https://www.youtube-nocookie.com/embed/'.$id : null;
    }

    public static function idDariUrl(?string $url): ?string
    {
        $parts = parse_url((string) $url);
        if (! is_array($parts)) {
            return null;
        }

        $host = strtolower($parts['host'] ?? '');
        if (! in_array($parts['scheme'] ?? '', ['http', 'https'], true)) {
            return null;
        }

        $path = trim($parts['path'] ?? '', '/');
        $id = null;

        if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
            if ($path === 'watch') {
                parse_str($parts['query'] ?? '', $query);
                $id = $query['v'] ?? null;
            } else {
                $segments = explode('/', $path);
                if (in_array($segments[0] ?? '', ['embed', 'shorts', 'live', 'v'], true)) {
                    $id = $segments[1] ?? null;
                }
            }
        } elseif (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            $id = explode('/', $path)[0] ?? null;
        }

        return is_string($id) && preg_match('/^[A-Za-z0-9_-]{11}$/', $id) ? $id : null;
    }
}
