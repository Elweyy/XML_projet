<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$auth = 'barottin2u';
$opts = array('http' => array(
    'proxy' => 'tcp://127.0.0.1:8080',
    'request_fulluri' => true,
));

$context = stream_context_create($opts);
// $default = stream_context_set_default($opts);
$gps = get_gps();
$urlMeteo = "http://www.infoclimat.fr/public-api/gfs/xml?_ll=" . $gps . "&_auth=Bx0AFwZ4VHZRfFNkVyEELVkxVWBdKwEmBXkLaAlsB3oCaVc2A2NXMQdpVClTfFFnVntUN1tgBTVUPwB4WihePwdtAGwGbVQzUT5TNld4BC9Zd1U0XX0BJgVhC2sJegdlAmJXNQN%2BVzQHYVQ%2BU31RZFZgVD1bewUiVDYAYlozXjkHYgBmBmZUN1E6UzZXeAQvWW9VZl1jATgFZwtoCWYHNgJiVzYDNVdjB2lUMVN9UWVWYFQxW2YFP1QyAGNaNF4iB3sAHQYWVCtRflNzVzIEdll3VWBdPAFt&_c=9f5a1e85156d5504420caa6e8c1b401d";

// $response = file_get_contents($urlMeteo, false, $context);
$response = file_get_contents($urlMeteo);
// phpinfo();

if (isset($http_response_header) && strpos($http_response_header[0], '200')) {
    $xmlFilename = "meteo.xml";
    $xslFilename = "meteo.xsl";
    file_put_contents($xmlFilename, $response);

    // $command = "xsltproc ".$xslFilename." ".$xmlFilename." > meteo.html";
    // $last_line = system($command,$retval);
    // readfile("meteo.html");

    $xml = new DOMDocument();
    $xml->load($xmlFilename);
    $xsl = new DOMDocument();
    $xsl->load($xslFilename);
    $proc = new XSLTProcessor();
    $proc->importStyleSheet($xsl);
    echo $proc->transformToXML($xml);

} else {
    echo 'ECHEC';
}

// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, $urlMeteo);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// curl_setopt($ch, CURLOPT_PROXY, 'tcp://127.0.0.1:8080');

// $response = curl_exec($ch);
// $info = curl_getinfo($ch);
// curl_close($ch);

// if ($info['http_code'] === 200) {
//     $xmlFilename = "meteo.xml";
//     $xslFilename = "meteo.xsl";
//     file_put_contents($xmlFilename, $response);

//     // $command = "xsltproc ".$xslFilename." ".$xmlFilename." > meteo.html";
//     // $last_line = system($command,$retval);
//     // readfile("meteo.html");

//     $xml = new DOMDocument();
//     $xml->load($xmlFilename);
//     $xsl = new DOMDocument();
//     $xsl->load($xslFilename);
//     $proc = new XSLTProcessor();
//     $proc->importStyleSheet($xsl);
//     echo $proc->transformToXML($xml);

// } else {
//     echo $info['http_code'];
// }
/**
 * Retourne l'ip du client
 */
function get_ip()
{
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false);
    }
}
/**
 * Retourne les coordonnées gps
 */
function get_gps()
{
    $ip = get_ip();
    if (get_ip() == false) {
        $res = '48.692054,6.184417';
    } else {
        $coord = json_decode(file_get_contents("http://ip-api.com/json/" . $ip));
    }
    if ($coord->status == 'fail') {
        $res = '48.692054,6.184417';
    } else {
        $res = $coord->lat . ',' . $coord->lon;
    }
    return $res;
}
