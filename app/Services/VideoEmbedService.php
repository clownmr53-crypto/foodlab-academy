<?php

namespace App\Services;

class VideoEmbedService
{
    public function embedUrl(?string $provider, ?string $videoId): ?string
    {
        if (! $provider || ! $videoId) {
            return null;
        }

        $provider = strtolower($provider);

        return match ($provider) {
            'bunny' => $this->bunnyUrl($videoId),
            'vimeo' => 'https://player.vimeo.com/video/'.urlencode($videoId).'?title=0&byline=0&portrait=0',
            default => null,
        };
    }

    public function iframeHtml(?string $provider, ?string $videoId): string
    {
        $url = $this->embedUrl($provider, $videoId);
        if (! $url) {
            return '<p class="text-gray-500">Vidéo non configurée.</p>';
        }

        $safe = e($url);

        return '<div class="aspect-video w-full overflow-hidden rounded-lg bg-black">'
            .'<iframe src="'.$safe.'" class="h-full w-full" allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>'
            .'</div>';
    }

    protected function bunnyUrl(string $videoId): string
    {
        $library = config('foodlab.video.bunny.library_id');
        if ($library) {
            return 'https://iframe.mediadelivery.net/embed/'.urlencode((string) $library).'/'.urlencode($videoId).'?autoplay=false';
        }

        return 'https://iframe.mediadelivery.net/play/'.urlencode($videoId);
    }
}
