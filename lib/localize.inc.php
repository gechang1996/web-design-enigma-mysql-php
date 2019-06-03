<?php
/**
 * Function to localize our site
 * @param $site The Site object
 */
return function(Enigma\Site $site) {
// Set the time zone
    date_default_timezone_set('America/Detroit');
    $site->setEmail('gechang1@cse.msu.edu');
    $site->setRoot('/~gechang1/project2');
    $site->dbConfigure('mysql:host=mysql-user.cse.msu.edu;dbname=gechang1',
        'gechang1',       // Database user
        'gechang1996',     // Database password
        'enigma_');            // Table prefix
};