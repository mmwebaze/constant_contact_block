<?php

namespace Drupal\constant_contact_block\services;

use Drupal\constant_contact_block\items\ContactList;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Config\ConfigFactory;
use Symfony\Component\HttpFoundation\RequestStack;

class ConstantContactManager implements ConstantContactInterface {
  private $baseUrl;
  private $apiKey;
  private $token;
  private $header;
  protected $client;
  protected $requestStack;

  /**
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  public function __construct(Client $client, ConfigFactory $configFactory, RequestStack $requestStack) {
    $this->client = $client;
    $request = $requestStack->getCurrentRequest();
    $session = $request->getSession();
    $this->token = $session->get('access_token');
    $this->header = ['headers' => [
      'Authorization' => 'Bearer ' .$this->token,
      'Content-Type' => 'application/json',
      'Accept' => 'application/json'
    ]];
    $this->configFactory = $configFactory->getEditable('constant_contact_block.constantcontantconfig');
    $this->baseUrl = $this->configFactory->get('base_url');
    $this->apiKey = $this->configFactory->get('api_key');
  }
  /*private function setAuthorizationToken($token){
    $header = ['headers' => [
      'Authorization' => 'Bearer ' .$token,
      'Content-Type' => 'application/json',
      'Accept' => 'application/json'
    ]];

    return $header;
  }*/
  public function addContact($contact){
    $endPoint = 'contacts?api_key='.$this->apiKey;
    try{
      $response = $this->client->post( $this->baseUrl.$endPoint,
        [
          'debug' => TRUE,
          'body' => json_encode($contact),
          'headers' => $this->header['headers']
        ]);
      $response->getBody()->getContents();
    }
    catch (RequestException $e) {
      // log error $e
      drupal_set_message($e->getMessage());
      return;
    }
  }
  public function getContacts(){
    $endPoint = 'contacts?status=ALL&limit=50&api_key='.$this->apiKey;
    try{
      $result = $this->client->request('GET', $this->baseUrl.$endPoint, $this->header);
      return $result->getBody()->getContents();
    }
    catch (RequestException $e) {
      // log error $e
      drupal_set_message($e->getMessage());
      return;
    }
  }
  public function getContactLists(){
    //$this->authenticationService->login('', '');
    $endPoint = 'lists?api_key='.$this->apiKey;
    try{
      $result = $this->client->request('GET', $this->baseUrl.$endPoint, $this->header);
      return $result->getBody()->getContents();
    }
    catch (RequestException $e) {
      // log error $e
      drupal_set_message($e->getMessage());
      return;
    }
  }
  public function addContactList($name, $status = 'ACTIVE'){

    $contactList = new ContactList($name, $status);
    $endPoint = 'lists?api_key='.$this->apiKey;
    try{
      $response = $this->client->post( $this->baseUrl.$endPoint,
        [
          'debug' => TRUE,
          'body' => json_encode($contactList),
          'headers' => [
            'Authorization' => 'Bearer ' .$this->token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
          ]
        ]);
      return $response->getBody()->getContents();
    }
    catch (RequestException $e) {
      // log error $e
      drupal_set_message($e->getMessage());
      return;
    }
  }
}