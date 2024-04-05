<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>probe.wtf</title>
    <meta name="og:image" content="{{ $nounPng }}" />

    <!-- Required Frame Properties -->
    <meta name="fc:frame" content="vNext" />
    <meta name="fc:frame:image" content="{{ $nounPng }}" />    
    <meta name="fc:frame:image:aspect_ratio" content="1:1" />

    <!-- Optional Properties for Interaction -->
    <meta name="fc:frame:button:1" content="{{ $noun->token_id }}" />
    <meta name="fc:frame:button:1:action" content="link" />
    <meta name="fc:frame:button:1:target" content="https://nouns.wtf/noun/{{ $noun->token_id }}" />

    <meta name="fc:frame:button:2" content="probe.wtf" />
    <meta name="fc:frame:button:2:action" content="link" />
    <meta name="fc:frame:button:2:target" content="https://probe.wtf/nouns" />

    @if ($noun->token_id >= 0)
        @if ($noun->token_id > 0)
            <meta name="fc:frame:button:3" content="Previous" />
            <meta name="fc:frame:button:3:action" content="post" />
            <meta name="fc:frame:button:3:post_url" content="https://api.probe.wtf/api/warpcast-frames/previous-noun/{{ $noun->token_id }}" />
        @endif

        @if ($noun->token_id > 0 && $hasMore) 
            <meta name="fc:frame:button:4" content="Next" />
            <meta name="fc:frame:button:4:action" content="post" />
            <meta name="fc:frame:button:4:post_url" content="https://api.probe.wtf/api/warpcast-frames/next-noun/{{ $noun->token_id }}" />
        @elseif ($hasMore)
            <meta name="fc:frame:button:3" content="Next" />
            <meta name="fc:frame:button:3:action" content="post" />
            <meta name="fc:frame:button:3:post_url" content="https://api.probe.wtf/api/warpcast-frames/next-noun/{{ $noun->token_id }}" />
        @endif
    @endif
</head>
<body>
    <p>go to <a href="https://www.probe.wtf">probe.wtf</a></p>
</body>
</html>