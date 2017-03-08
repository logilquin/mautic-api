<?php

namespace AppBundle\Service\Emailing;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\CRM\Opportunite;
use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;

class MauticToolsServices extends ContainerAware
{
    protected $session;
    protected $em;

    public function __construct(Session $session, EntityManager $em)
    {
        $this->session = $session;
        $this->em = $em;
    }

    public function mauticConnexion($baseUrl, $publicKey, $secretKey, $callback = 'http://localhost:8000/mautic/connexion/success')
    {

        $apiUrl = $baseUrl . "/api/";

        $settings = array(
            'baseUrl' => $baseUrl,       // Base URL of the Mautic instance
            'version' => 'OAuth2', // Version of the OAuth can be OAuth2 or OAuth1a. OAuth2 is the default value.
            'clientKey' => $publicKey,       // Client/Consumer key from Mautic
            'clientSecret' => $secretKey,       // Client/Consumer secret key from Mautic
            'callback' => $callback        // Redirect URI/Callback URI for this script
        );
        $initAuth = new ApiAuth();
        $auth = $initAuth->newAuth($settings);

        try {
            $this->session->set('auth', $auth);
            $this->session->set('url', $apiUrl);

            if($auth->validateAccessToken()){

            }

        } catch (\Exception $e) {
            // Do Error handling
            return;
        }
    }

//    public function mauticAddContact($nom, $prenom, $mail){
//
//        $auth = $this->session->get('auth');
//        $url = $this->session->get('url');
//        $token = $this->session->get('accessToken');
//
//
//        $data = array(
//            'firstname' => $nom,
//            'lastName' => $prenom,
//            'email' => $mail
//        );
//
//        $api = new MauticApi();
//        $contactApi = $api->newApi('contacts', $auth, $url);
//        $response = $contactApi->create($data);
//        return $response;
//
//    }

    public function mauticConnexionCheck(){

        $auth = $this->session->get('auth');
        if ($auth->validateAccessToken()) {
            if ($auth->accessTokenUpdated()) {
                $accessToken = $auth->getAccessTokenData();
                $this->session->set('accessToken', $accessToken);
                $content = 'Vos données ont bien été enregistrées';
                return $content;
            }
            else{
                $accessToken = $auth->getAccessTokenData();
                $this->session->set('accessToken', $accessToken);
                $content = 'Vos données ont bien été enregistrées';
                return $content;
            }
        }
        else{
            return 'error';
        }
    }

//    public function mauticAuth0Expires($token){
//
//
//        //TODO  verifier temps restant avant l'expiration du token et rediriger refaire une requete aupres de mautic
//
//        //TODO Stocker les informations (keys) dans le constructeur?
//
//    }

}