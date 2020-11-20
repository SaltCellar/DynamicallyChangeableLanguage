<?php

    // ******************************************************
    // // // // // // Default & Detected // // // // // //
    // ******************************************************

    $LANGUAGES_AVILABLE     = ['hu','us','de'];
    $LANGUAGE_DEFAULT       = $LANGUAGES_AVILABLE[0];
    $LANGUAGE_DETECTED      = false;

    if (file_exists('php/geoPlugin.php')) {

        require('php/geoPlugin.php');

        $geoData = getClientGeoData( getClientAddress() );

        if ( $geoData !== false && isset($geoData['alpha2']) ) {
    
            $LANGUAGE_DETECTED = strtolower($geoData['alpha2']);
    
            if ( !in_array($LANGUAGE_DETECTED, $LANGUAGES_AVILABLE) ) {
                $LANGUAGE_DETECTED = $LANGUAGE_DEFAULT;
            }
            
        }

    }

    // ******************************************************
    // // // // // // Ajax // // // // // //
    // ******************************************************

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        response(false,false,false,
            'Request rejected! Invalid method. 🚨'
        );
    }

    if( !isset($_POST['INFO']) || !isset($_POST['DATA']) ) {
        response(false,false,false,
            'Request rejected! Missing parameters. 🙁'
        );
    }

    $_POST['INFO'] = json_decode($_POST['INFO'],true);
    $_POST['DATA'] = json_decode($_POST['DATA'],true);

    // ******************************************************
    // // // // // // Ajax Endpoint // // // // // //
    // ******************************************************
    

    //Adott Tartalom és hozzá kapcsolódó nyelvek. (LtoC)
    //LtoC();

    //Adott nyelv és a hozzá kapcsolódó tartalmak. (CtoL) 
    CtoL();


    // LtoC
    function LtoC() {

        $res = [];
        $lang = $_POST['INFO'];
        $langFilePath = 'lang/LtoC/';

        foreach($_POST['DATA'] as $k => $v) {

            $file = $langFilePath . $k . '.json';
            $fileParsed = [];

            if (file_exists($file)) {
                $fileParsed = json_decode(file_get_contents($file),true);

                foreach($v as $e) {

                    if (isset($fileParsed[$e][$lang])) {

                        $res[$k.".".$e] = $fileParsed[$e][$lang];

                    }

                }

            }

        }

        response(true,false,$res);

    }

    // CtoL
    function CtoL() {

        $res = [];
        $lang = $_POST['INFO'];
        $langFilePath = 'lang/CtoL/' . $lang . '/';

        foreach($_POST['DATA'] as $k => $v) {

            $file = $langFilePath . $k . '.json';
            $fileParsed = [];

            if (file_exists($file)) {
                $fileParsed = json_decode(file_get_contents($file),true);

                foreach($v as $e) {

                    if (isset($fileParsed[$e])) {

                        $res[$k.".".$e] = $fileParsed[$e];

                    }

                }

            }

        }

        response(true,false,$res);

    }


    // ******************************************************
    // // // // // // Ajax Response // // // // // //
    // ******************************************************

    function response(
        $_status,
        $_info      = false,
        $_data      = false,
        $_error     = false,
        $_success   = false,
        $_default   = false
    ) {
        header('Content-Type: application/json');
        print json_encode([
            'status'    => $_status,
            'info'      => $_info,
            'data'      => $_data,
            'error'     => $_error,
            'success'   => $_success,
            'default'   => $_default
        ]);
        exit(0);
    }

?>