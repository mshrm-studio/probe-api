<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>probe.wtf</title>
    <meta property="og:image" content="{{ $nounPng }}" />

    <!-- Required Frame Properties -->
    <meta property="fc:frame" content="vNext" />
    <meta property="fc:frame:image" content="{{ $nounPng }}" />    
    <meta property="fc:frame:image:aspect_ratio" content="1:1" />

    <!-- Optional Properties for Interaction -->
    <meta property="fc:frame:button:1" content="Noun #{{ $noun->token_id }}" />
    <meta property="fc:frame:button:1:action" content="link" />
    <meta property="fc:frame:button:1:target" content="https://nouns.wtf/noun/{{ $noun->token_id }}" />

    <meta property="fc:frame:button:2" content="probe.wtf" />
    <meta property="fc:frame:button:2:action" content="link" />
    <meta property="fc:frame:button:2:target" content="https://probe.wtf/nouns" />

    <meta property="fc:frame:button:3" content="Random Noun" />
    <meta property="fc:frame:button:3:action" content="post" />
    <meta property="fc:frame:button:3:post_url" content="https://api.probe.wtf/api/warpcast-frames/nouns/random" />
</head>
<body>
    <p>go to <a href="https://www.probe.wtf">probe.wtf</a></p>
</body>
</html>