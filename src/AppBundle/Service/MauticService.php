<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;
use Symfony\Component\HttpFoundation\Session\Session;


class MauticService
{

  public $session;

  public function __construct(Session $session){
    $this->session = $session;
  }

  public function authorization(){

    $publicKey = '1_1gyt683n8nq8gko0wcs84gwg0wo8o4ogwswckww440gswwo8ow';
    $secretKey = '66n1nedu1c4kckc444cokgkso8w04gcckskck4wwwcskg444cs';
    $callback  = 'http://dev.mautic-api.com/web/app_dev.php/home';

    // ApiAuth->newAuth() will accept an array of Auth settings
    $settings = array(
        'baseUrl'          => 'https://nicomak.mautic.net',   // Base URL of the Mautic instance
        'version'          => 'OAuth2',                       // Version of the OAuth can be OAuth2 or OAuth1a. OAuth2 is the default value.
        'clientKey'        => $publicKey,                    // Client/Consumer key from Mautic
        'clientSecret'     => $secretKey,                    // Client/Consumer secret key from Mautic
        'callback'         => $callback                     // Redirect URI/Callback URI for this script
    );

    // Initiate the auth object
    $initAuth = new ApiAuth();
    $auth = $initAuth->newAuth($settings);

    // Initiate process for obtaining an access token; this will redirect the user to the $authorizationUrl and/or
    // set the access_tokens when the user is redirected back after granting authorization

    // If the access token is expired, and a refresh token is set above, then a new access token will be requested
    try {
        if ($auth->validateAccessToken()) {

            // Obtain the access token returned; call accessTokenUpdated() to catch if the token was updated via a
            // refresh token

            // $accessTokenData will have the following keys:
            // For OAuth2: access_token, expires, token_type, refresh_token

            if ($auth->accessTokenUpdated()) {
                $accessTokenData = $auth->getAccessTokenData();

                //store access token data however you want
                $this->session->set('accessTokenData', $accessTokenData);
                $this->session->set('auth', $auth);
            }
        }
    } catch (Exception $e) {
        // Do Error handling
        throw $e;
    }
  }

  public function createContact($contact){

    $api = new MauticApi();

    // Create an api context by passing in the desired context (Contacts, Forms, Pages, etc), the $auth object from above
    // and the base URL to the Mautic server (i.e. http://my-mautic-server.com/api/)
    $contactApi = $api->newApi('contacts', $this->session->get('auth'), 'https://nicomak.mautic.net/api');

    try {
      $contact = $contactApi->create($contact);
    } catch (Exception $e) {
        // Do Error handling
        throw $e;
    }

  }

  public function createContactBatch($contacts){

    $api = new MauticApi();

    // Create an api context by passing in the desired context (Contacts, Forms, Pages, etc), the $auth object from above
    // and the base URL to the Mautic server (i.e. http://my-mautic-server.com/api/)
    $contactApi = $api->newApi('contacts', $this->session->get('auth'), 'https://nicomak.mautic.net/api');

    try {
      foreach($contacts as $contact){
        $contact = $contactApi->create($contact);
      }
    } catch (Exception $e) {
        // Do Error handling
        throw $e;
    }

  }

}
