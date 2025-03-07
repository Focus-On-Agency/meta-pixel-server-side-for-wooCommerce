<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

return [
    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => 'Meta Pixel Serverside for Woocommerce',

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */
    'env' =>  'development',

    'capability' => 'manage_options',
    
    'slug' => 'focuson-mpsfw',
    
    'id' => 'focuson_mpsfw',
];
