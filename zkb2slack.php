<?php

$queueID = (string) getenv("queueID");
$slackHookURL = (string) getenv("slackHookURL");
$channel = (string) getenv("channel");
$name = (string) getenv("name");

do {
    $result = json_decode(doCurl("https://redisq.zkillboard.com/listen.php?ttw=3&queueID=$queueID"), true);

    $sendMail = findName(@$result['package']['killmail']['victim'], $name);
    foreach((array) $result['package']['killmail']['attackers'] as $attacker) {
        $sendMail |= findName($attacker, $name);
    }

    if ($sendMail) {
        $killID = $result['package']['killID'];
        doCurl($slackHookURL, ['text' => "<https://zkillboard.com/kill/$killID/>", 'unfurl_links' => true], 'POST');
    }
} while (isset($result['package']['killID']));

function findName($entity, $name)
{
    return @$entity['corporation']['name'] === $name || @$entity['alliance']['name'] === $name;
}

function doCurl($url, $fields = [], $callType = 'GET')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, "curl fetcher for zkb-2-slack");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    if ($callType == 'POST') {
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields, JSON_UNESCAPED_SLASHES));
    }
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $callType);
    return curl_exec($ch);
}
