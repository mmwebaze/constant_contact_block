<?php

namespace Drupal\constant_contact_block\services;

use Drupal\constant_contact_block\items\ContactList;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Messenger\Messenger;

class ConstantContactManager implements ConstantContactInterface {
  private $response;
  private $baseUrl;
  private $apiKey;
  private $token;
  private $header;
  protected $client;
  protected $messenger;

  /**
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  public function __construct(Client $client, ConfigFactory $configFactory, Messenger $messenger) {
    $this->client = $client;
    $this->messenger = $messenger;
    $this->configFactory = $configFactory->getEditable('constant_contact_block.constantcontantconfig');
    $this->token = $this->configFactory->get('auth_token');
    $this->header = ['headers' => [
      'Authorization' => 'Bearer ' .$this->token,
      'Content-Type' => 'application/json',
      'Accept' => 'application/json'
    ]];

    $this->baseUrl = $this->configFactory->get('base_url');
    $this->apiKey = $this->configFactory->get('api_key');
  }

  public function addContact($contact){
    $endPoint = 'contacts?api_key='.$this->apiKey;
    try{
      $response = $this->client->post( $this->baseUrl.$endPoint,
        [
          'debug' => TRUE,
          'body' => json_encode($contact),
          'headers' => $this->header['headers']
        ]);
      return $response->getBody()->getContents();
    }
    catch (RequestException $e) {
      // log error $e
      $this->messenger->addMessage($e->getMessage());
      return $this;
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
      $this->messenger->addMessage($e->getMessage());
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
      $this->messenger->addMessage($e->getMessage());
      return;
    }
  }
  public function addContactList($name, $status = 'ACTIVE'){

    $contactList = new ContactList($name, $status);
    $endPoint = 'lists?api_key='.$this->apiKey;
    try{
      $this->response = $this->client->post( $this->baseUrl.$endPoint,
        [
          'debug' => TRUE,
          'body' => json_encode($contactList),
          'headers' => [
            'Authorization' => 'Bearer ' .$this->token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
          ]
        ]);
      return $this->response->getBody()->getContents();
    }
    catch (RequestException $e) {
      // log error $e
      $this->messenger->addMessage($e->getMessage());
      return;
    }
  }
  public function deleteContactList($listId){
    $endPoint = 'lists/'.$listId.'?api_key='.$this->apiKey;
    try{
      $this->response = $this->client->request('DELETE', $this->baseUrl.$endPoint, $this->header);

      //return
    }
    catch (RequestException $e) {
      // log error $e
      $this->messenger->addMessage($e->getMessage());
      return;
    }
  }
}