<?php
 
    function getClientAddress() {
        foreach (array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ) as $key) {
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe

                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return false;
    }

    function getClientGeoData($address,$apiUrl = 'https://api.ipgeolocationapi.com/geolocate/') {

        if( is_string($address) ) {

            $geoData = false;
            $url = $apiUrl . $address; 
    
            //'https://api.ipgeolocationapi.com/geolocate/' . $ip
            //'http://www.geoplugin.net/json.gp?ip=' . $ip
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $response = curl_exec($ch);
    
            if(!curl_errno($ch)){
                $geoData = json_decode($response,true);
            }
            
            curl_close($ch);

            return $geoData;
    
        }

    }

?>