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
    <meta property="fc:frame:button:1" content="Probe Nouns" />
    <meta property="fc:frame:button:1:action" content="post" />
    <meta property="fc:frame:button:1:post_url" content="https://api.probe.wtf/api/warpcast-frames/random-noun" />
</head>
<body>
    <h1>probe.wtf</h1>
</body>
</html>