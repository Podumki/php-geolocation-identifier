<?php
$currentUrl = $_POST['url'];

$result = dns_get_record($currentUrl);

$dnsTypeA = [];
$dnsTypeSOA = [];
foreach($result as $dnsRecord) {
    if($dnsRecord['type'] == 'A') {
        $dnsTypeA = $dnsRecord;
    } else {
        if ($dnsRecord['type'] == 'SOA') {
            $dnsTypeSOA = $dnsRecord;
        }
    }
}
if (count($dnsTypeA)) {
} else {
    $secodTry = dns_get_record($dnsTypeSOA['rname']);
    foreach($secodTry as $try) {
        if($try['type'] == 'A') {
            $dnsTypeA = $try;
        } 
    }
}

$urlstart = curl_init('http://ipwho.is/'.$dnsTypeA['ip']);
curl_setopt($urlstart , CURLOPT_RETURNTRANSFER, true);
curl_setopt($urlstart , CURLOPT_HEADER, false);
$ipwhois = json_decode(curl_exec($urlstart ), true);
curl_close($urlstart );
print_r('IP-Adress: ' . $ipwhois['ip'] . 'Country: ' . $ipwhois['country'] . ' City: ' . $ipwhois['city']);
?>