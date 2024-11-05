<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Lil Nouns Explorer</title>
        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.tsx'])

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    </head>

    <body class="antialiased">
        <nav id="lil-noun-list-filters"></nav>

        <ul style="display:flex;flex-wrap:wrap;">
            @foreach ($lilNouns as $lilNoun)
                @if (!empty($lilNoun))
                    <li style="display:flex;margin-bottom:20px;margin-right:20px;">
                        @php
                            $prefix = "data:application/json;base64,";
                            $jsonString = base64_decode(str_replace($prefix, '', $lilNoun->token_uri));
                            $jsonObject = json_decode($jsonString);
                            $imageSource = $jsonObject->image ?? '';
                        @endphp

                        @if(!empty($imageSource))
                            <img src="{{ $imageSource }}" alt="Image description here" height="75" width="75" style="margin-right:20px;">
                        @else
                            <div style="background-color:black;width:75px;height:75px;margin-right:20px;"></div>
                        @endif

                        {{-- <img src="https://noun.pics/{{ $lilNoun->token_id }}.svg" height="100" width="100" /> --}}

                        <div style="width:250px;">
                            <p style="margin: 1px 0;">token id: {{ $lilNoun->token_id ?? '-' }}</p>
                            <p style="margin: 1px 0;">background: {{ $lilNoun->background_name ?? '-' }}</p>
                            <p style="margin: 1px 0;">head: {{ $lilNoun->head_name ?? '-' }}</p>
                            <p style="margin: 1px 0;">accessory: {{ $lilNoun->accessory_name ?? '-' }}</p>
                            <p style="margin: 1px 0;">body: {{ $lilNoun->body_name ?? '-' }}</p>
                            <p style="margin: 1px 0;">glasses: {{ $lilNoun->glasses_name ?? '-' }}</p>
                        </div> 
                    </li>
                @endif
            @endforeach
        </ul>
    </body>
</html>
