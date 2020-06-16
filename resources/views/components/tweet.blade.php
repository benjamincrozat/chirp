<div class="relative @empty($quote) bg-gray-700 px-4 py-8 sm:p-8 rounded shadow @else bg-gray-800 bg-opacity-50 p-4 rounded-sm @endif">
    <div class="sm:flex sm:items-center sm:justify-between">
        <a href="{{ $tweet->userUrl() }}" target="_blank" class="flex items-center sm:pr-4 hover:text-yellow-500">
            <img src="{{ $tweet->userAvatar() }}" width="48" height="48" class="flex-none rounded-full">

            <div class="leading-tight pl-4">
                <p class="font-bold">{{ $tweet->data->user->name }}</p>
                <p>&#x40;{{ $tweet->data->user->screen_name }}</p>
            </div>
        </a>

        <p class="mt-4 sm:mt-0 sm:pl-4 sm:text-right text-gray-500 text-sm">
            {{ $tweet->date() }}
        </p>
    </div>

    @if ($tweet->data->in_reply_to_status_id)
        <div class="text-center">
            <p class="bg-gray-800 bg-opacity-50 mt-8 px-4 py-3 rounded">
                In reply to <a href="{{ $tweet->parentTweetUrl() }}" class="font-semibold hover:text-yellow-500">a tweet</a> from <a href="{{ $tweet->parentTweetAuthorUrl() }}" class="font-semibold hover:text-yellow-500">{{ $tweet->data->in_reply_to_screen_name }}</a>
            </p>

            <x-zondicon-arrow-thick-down class="inline-block h-8 mt-8 text-gray-500" />
        </div>
    @endif

    <p class="hyphens mt-8">
        {!! $tweet->text() !!}
    </p>

    @if ($quotedStatus = $tweet->quotedStatus())
        <div class="block mt-8">
            <x-tweet :tweet="$quotedStatus" :quote="true" />
        </div>
    @endif

    @if ($tweet->media()->isNotEmpty())
        <div class="flex mt-6">
            @foreach ($tweet->media() as $media)
                <a href="{{ $media->url }}" class="@if (! $loop->first) ml-2 @endif flex-grow hover:opacity-75">
                    <img loading="lazy" src="{{ $media->media_url_https }}?name=medium" width="{{ $media->sizes->medium->w }}" height="{{ $media->sizes->medium->h }}" class="h-full object-center object-cover">
                </a>
            @endforeach
        </div>
    @endif

    @empty($quote)
        <p class="mt-8 text-center">
            <a href="{{ $tweet->url() }}" target="_blank" rel="noopener" class="font-semibold hover:text-yellow-500">More on Twitter</a>
        </p>
    @endif
</div>
