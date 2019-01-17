<?php    
    ini_set('display_errors', 1);
    //Passage du proxy
    $opts = array(
        'http' => array(
            'proxy'=> 'www-cache.iutnc.univ-lorraine.fr:3128', 
            'request_fulluri'=> true));
    $context = stream_context_set_default($opts);   
    
    //Ip depuis internet
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   
    {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    }
    //Ip depuis un proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
    {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    //Ip depuis une addresse éloignée
    else
    {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    
    $geolocip_url="http://ip-api.com/xml/".$ip_address;

    // On le géolocalise
    $xml_geoloc = getXML($geolocip_url);    
    $geoloc = simplexml_load_string($xml_geoloc);

    //On instancie les variable
    $meteo_url="http://www.infoclimat.fr/public-api/gfs/xml?_ll=".$geoloc->lat.",".$geoloc->lon."&_auth=U0kEEw5wXH5TfltsBnBVfFY%2BVWBeKAAnAHwHZFw5XiMIYwRlUzMBZ1U7A34CLQM1Ay4FZlphADBROgtzWykEZVM5BGgOZVw7UzxbPgYpVX5WeFU0Xn4AJwBiB2lcMl4jCG4EYFMuAWJVOQNkAiwDNgM5BW1aegAnUTMLaVsyBGNTNgRiDmxcO1M8WzoGKVV%2BVmBVZl5gADkAYwc0XGNeawg%2FBDJTYgFiVTwDZAIsAzUDNAVtWmYAPVEyC2lbNgR4Uy8EGQ4eXCNTfFt7BmNVJ1Z4VWBePwBs&_c=dee6237f368c6c424aefae2ef39fb1bc";    
    $velo_url="http://www.velostanlib.fr/service/carto";    
    $continu = true;

    $xslt = new XSLTProcessor();    

    // On recupere les XML
    while($continu==true){
        //Import de la meteo
        $content_meteo = getXML($meteo_url);    
        $xml_meteo = simplexml_load_string($content_meteo);        
        $xsl_meteo = new DOMDocument();
        $xsl_meteo->load('meteo.xsl');
        $xslt->importStylesheet($xsl_meteo);
        $meteo = $xslt->transformToXML($xml_meteo);        
        
        //Import pour les informations des vélos
        $content_velo = getXML($velo_url);
        $xml_velo = simplexml_load_string($content_velo);                   

        $add_marker=null;

        for($i=0;$i<sizeof($xml_velo->markers->marker);$i++){                       
            $content_marker = getXML("http://www.velostanlib.fr/service/stationdetails/nancy/".$xml_velo->markers->marker[$i]['number']);
            $xml_marker = simplexml_load_string($content_marker);            
            $add_marker .= 'addMarker("'.$xml_velo->markers->marker[$i]['name'].'",'.$xml_velo->markers->marker[$i]['lat'].','.$xml_velo->markers->marker[$i]['lng'].','.$xml_marker->available.','.$xml_marker->free.');';                        
        }

        echo '<HTML>
                <head>                
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
                    integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
                    crossorigin=""/> 
                    <link rel="stylesheet" href="style.css"/>
                    <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
                    integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
                    crossorigin=""></script> 
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">                                        
                </head>
                <body> 
                    '.$meteo.'                   
                    <div style="height:500px;width:500px;" id="mapid"></div>
                    <script src="map.js"></script>
                    <script>
                        initmap('.$geoloc->lat.','.$geoloc->lon.');
                        '.$add_marker.'
                    </script>                        
                </body>
            </HTML>';                                                
        $continu = false;
    }

    function getXML($url){
        $content = file_get_contents($url);

        // OK
        if(checkCode(200,$http_response_header)){
            return $content;
        }

        //ERROR
        else{            
            if(checkCode(500,$http_response_header)){
                echo '<h1>Impossible d\'accéder au serveur</h1>';                
            }
            else if(checkCode(404,$http_response_header)){
                echo '<h1>Page inaccessible</h1>';
            }
            else if(checkCode(403,$http_response_header)){
                echo '<h1>Acces interdit</h1>';
            }
            $continu = false;
            return null;
        }
    }
    
    function checkCode($code,$header_response){     
        if(explode(" ",$header_response[0])[1]==$code){         
            return true;
        }
        return false;
    }
?>