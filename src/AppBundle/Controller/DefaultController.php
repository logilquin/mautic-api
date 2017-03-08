<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Mautic\Auth\ApiAuth;
use Symfony\Component\HttpFoundation\Session\Session;


class DefaultController extends Controller
{


    /**
     * @Route("/stats", name="stats")
     */
    public function stats(){

      //  $this->get('session')->set('accessTokenData', null);
      //  $this->get('session')->set('redirection', null); return 0;

      if($this->get('session')->get('accessTokenData') === null){
        $this->get('session')->set('redirection', 'stats');
        return $this->redirect($this->generateUrl('homepage'));
      }


      dump($this->get('session')->get('accessTokenData'));

      echo 'Stats';

      return 0;

    }

    /**
     * @Route("/home", name="homepage")
     */
    public function indexAction(Request $request)
    {

      $mauticService = $this->get('mautic');
      try{
        $mauticService->authorization();
      } catch(\Exception $e){
        throw $e;
      }

      if($this->get('session')->get('redirection')){
        return $this->redirect($this->generateUrl($this->get('session')->get('redirection')));
      }

      return new Response();
    }

    /**
     * @Route("/contact/create", name="create_contact")
     */
    public function createContact(){

      $contact = array(
          'firstname' => 'Jim',
          'lastname'  => 'Contact',
          'email'     => 'jim@his-site.com'
      );

      $mauticService = $this->get('mautic');
      try{
        $mauticService->createContact($contact);
      } catch(\Exception $e){
        throw $e;
      }

      return new Response();

    }

    /**
     * @Route("/contact/create/batch", name="create_contact_batch")
     */
    public function createContactBatch(){

      $firstNames = array('Geoffroy', 'Céline', 'Mélanie', 'Séverin', 'Laetitia', 'Valène', 'Tatiana', 'Karen', 'Alice', 'Laura');
      $lastNames = array('Murat', 'Gindre', 'Danjou', 'Thiriot', 'Guibert', 'Salavin', 'Rinke', 'Vucher', 'Lousson', 'Gilquin');

      $contacts = array();
      for($i=0; $i<count($firstNames); $i++){
        $contacts[] = array(
          'firstname' => $firstNames[$i],
          'lastname' => $lastNames[$i],
          'email' => $lastNames[$i].'@nicomak.eu',
        );
      }

      $mauticService = $this->get('mautic');
      try{
        $mauticService->createContactBatch($contacts);
      } catch(\Exception $e){
        throw $e;
      }

      return new Response();

    }

}
