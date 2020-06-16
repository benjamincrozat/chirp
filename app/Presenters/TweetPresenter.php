<?php

namespace App\Presenters;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TweetPresenter
{
    public object $data;

    public function __construct(object $data)
    {
        $this->data = $data;
    }

    public function date() : string
    {
        return Carbon::parse($this->data->created_at)->isoFormat('LL');
    }

    public function media() : Collection
    {
        return collect($this->data->extended_entities->media ?? []);
    }

    public function parentTweetAuthorUrl() : string
    {
        return "https://twitter.com/{$this->data->in_reply_to_screen_name}";
    }

    public function parentTweetUrl() : string
    {
        return "https://twitter.com/{$this->data->in_reply_to_screen_name}/status/{$this->data->in_reply_to_status_id}";
    }

    public function quotedStatus() : ?self
    {
        if (empty($this->data->quoted_status)) {
            return null;
        }

        return new self($this->data->quoted_status);
    }

    public function text() : string
    {
        return $this->render('full_text');
    }

    public function url() : string
    {
        return "https://twitter.com/{$this->data->user->screen_name}/status/{$this->data->id}";
    }

    public function userAvatar() : string
    {
        return $this->data->user->profile_image_url_https;
    }

    public function userUrl() : string
    {
        return "https://twitter.com/{$this->data->user->screen_name}";
    }

    protected function render(string $key) : string
    {
        $text                = $this->data->$key;
        $stringsToReplace    = [];
        $stringsReplacements = [];

        foreach ($this->data->entities->user_mentions as $mention) {
            $stringsToReplace[]    = mb_substr($text, $mention->indices[0], $mention->indices[1] - $mention->indices[0]);
            $stringsReplacements[] = '<a href="https://twitter.com/' . $mention->screen_name . '" target="_blank" rel="noopener" class="font-semibold hover:text-yellow-500">@' . $mention->screen_name . '</a>';
        }

        foreach ($this->data->entities->hashtags as $hashtag) {
            $stringsToReplace[]    = mb_substr($text, $hashtag->indices[0], $hashtag->indices[1] - $hashtag->indices[0]);
            $stringsReplacements[] = '<a href="https://twitter.com/hashtag/' . $hashtag->text . '" target="_blank" rel="noopener" class="font-semibold hover:text-yellow-500">#' . $hashtag->text . '</a>';
        }

        foreach ($stringsToReplace as $key => $stringToReplace) {
            $text = mb_ereg_replace($stringToReplace, $stringsReplacements[$key], $text);
        }

        foreach ($this->data->entities->urls as $url) {
            $text = mb_ereg_replace($url->url, '<a href="' . $url->url . '" target="_blank" rel="noopener" class="font-semibold hover:text-yellow-500">' . $url->display_url . '</a>', $text);
        }

        if (! empty($this->data->extended_entities->media)) {
            foreach ($this->data->extended_entities->media as $media) {
                $text = mb_ereg_replace($media->url, '', $text);
            }
        }

        return nl2br($text);
    }
}
