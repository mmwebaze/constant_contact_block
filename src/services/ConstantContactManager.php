<?php

namespace Drupal\constant_contact_block\services;

use Drupal\constant_contact_block\items\ContactList;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 *
 */
class ConstantContactManager implements ConstantContactInterface {
  private $response;
  private $baseUrl;
  private $apiKey;
  private $token;
  private $header;
  /**
   * @var \GuzzleHttp\Client
   */
  protected $client;

  /**
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $logger;

  /**
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   *
   */
  public function __construct(Client $client, ConfigFactory $configFactory, LoggerChannelFactoryInterface $logger) {
    $this->client = $client;
    $this->logger = $logger->get('constant_contact_block');
    $this->configFactory = $configFactory->getEditable('constant_contact_block.constantcontantconfig');
    $this->token = $this->configFactory->get('auth_token');
    $this->header = [
      'headers' => [
        'Authorization' => 'Bearer ' . $this->token,
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
      ],
    ];

    $this->baseUrl = $this->configFactory->get('base_url');
    $this->apiKey = $this->configFactory->get('api_key');
  }

  /**
   * @inheritdoc
   */
  public function addContact($contact) {
    $endPoint = 'contacts?api_key=' . $this->apiKey;
    try {
      $response = $this->client->post($this->baseUrl . $endPoint,
        [
          'debug' => TRUE,
          'body' => json_encode($contact),
          'headers' => $this->header['headers'],
        ]);
      return $response->getBody()->getContents();
    }
    catch (RequestException $e) {
      // Log error $e.
      $this->logger->error($e->getMessage());
      return $this;
    }
  }

  /**
   * @inheritdoc
   */
  public function getContacts() {
    $endPoint = 'contacts?status=ALL&limit=50&api_key=' . $this->apiKey;
    try {
      $result = $this->client->request('GET', $this->baseUrl . $endPoint, $this->header);
      return $result->getBody()->getContents();
    }
    catch (RequestException $e) {
      // Log error $e.
      $this->logger->error($e->getMessage());
      return;
    }
  }

  /**
   * @inheritdoc
   */
  public function getContactList($listId) {
    $endPoint = 'lists/' . $listId . '?api_key=' . $this->apiKey;
    try {
      $result = $this->client->request('GET', $this->baseUrl . $endPoint, $this->header);
      return $result->getBody()->getContents();
    }
    catch (RequestException $e) {
      $this->logger->error($e->getMessage());
      return;
    }
  }

  /**
   * @inheritdoc
   */
  public function getContactLists() {
    $endPoint = 'lists?api_key=' . $this->apiKey;
    try {
      $result = $this->client->request('GET', $this->baseUrl . $endPoint, $this->header);
      return $result->getBody()->getContents();
    }
    catch (RequestException $e) {
      // Log error $e.
      $this->logger->error($e->getMessage());
      return;
    }
  }

  /**
   * @inheritdoc
   */
  public function addContactList($name, $status = 'ACTIVE') {

    $contactList = new ContactList($name, $status);
    $endPoint = 'lists?api_key=' . $this->apiKey;
    try {
      $this->response = $this->client->post($this->baseUrl . $endPoint,
        [
          'debug' => TRUE,
          'body' => json_encode($contactList),
          'headers' => [
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
          ],
        ]);
      return $this->response->getBody()->getContents();
    }
    catch (RequestException $e) {
      // Log error $e.
      $this->logger->error($e->getMessage());
      return;
    }
  }

  /**
   * @inheritdoc
   */
  public function deleteContactList($listId) {
    $endPoint = 'lists/' . $listId . '?api_key=' . $this->apiKey;
    try {
      $this->response = $this->client->request('DELETE', $this->baseUrl . $endPoint, $this->header);
    }
    catch (RequestException $e) {
      // Log error $e.
      $this->logger->error($e->getMessage());
      return;
    }
  }

  /**
   * @inheritdoc
   */
  public function updateContant($contact, array $lists, $isUpdateable = FALSE) {
    $endPoint = 'contacts/' . $contact->id . '?action_by=ACTION_BY_OWNER&api_key=' . $this->apiKey;
    $headers = [
      'headers' => [

        'Authorization' => 'Bearer ' . $this->token,
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
      ],
    ];
    $listIds = [];
    if (!$isUpdateable) {
      foreach ($contact->lists as $list) {
        array_push($listIds, $list->id);
      }
    }

    $contact->lists = $this->objectInArray($lists, $listIds);
    try {
      $response = $this->client->put($this->baseUrl . $endPoint, [
        'debug' => TRUE,
        'body' => json_encode($contact),
        'Content-Type' => 'application/json',
        'headers' => $headers['headers'],
      ]);

      return $response->getBody()->getContents();
    }
    catch (RequestException $e) {
      $this->logger->error($e->getMessage());
      return;
    }
  }

  /**
   * @inheritdoc
   */
  public function checkContactExistsByEmail($email) {
    $endPoint = 'contacts?email=' . urlencode($email) . '&status=ALL&limit=50&api_key=' . $this->apiKey;
    $headers = [
      'Authorization' => 'Bearer ' . $this->token,
    ];

    try {
      $response = $this->client->get($this->baseUrl . $endPoint, [
        'Content-Type' => 'application/json',
        'headers' => $headers,
      ]);
      $responseObj = json_decode($response->getBody()->getContents())->results[0];
      return $responseObj;
    }
    catch (RequestException $e) {
      $this->logger->error($e->getMessage());
      return;
    }
  }

  /**
   *
   */
  private function objectInArray($arr, array $listIds) {

    $updatedIds = $listIds;

    foreach ($arr as $item) {
      array_push($updatedIds, $item->id);
    }

    $updatedIds = array_unique($updatedIds);

    $updatedIdObj = [];
    foreach ($updatedIds as $updatedId) {
      $obj = new \stdClass();
      $obj->id = $updatedId;

      array_push($updatedIdObj, $obj);
    }

    return $updatedIdObj;
  }

  /**
   * @inheritdoc
   */
  public function getContactById($contactId) {
    $endPoint = 'contacts/' . $contactId . '?api_key=' . $this->apiKey;

    try {
      $result = $this->client->request('GET', $this->baseUrl . $endPoint, $this->header);
      return $result->getBody()->getContents();
    }
    catch (RequestException $e) {
      // Log error $e.
      $this->logger->error($e->getMessage());
      return;
    }
  }

  /**
   * @inheritdoc
   */
  public function deleteContact($contactId) {
    $endPoint = 'contacts/' . $contactId . '?api_key=' . $this->apiKey;

    try {
      $result = $this->client->request('DELETE', $this->baseUrl . $endPoint, $this->header);
      return $result->getBody()->getContents();
    }
    catch (RequestException $e) {
      $this->logger->error($e->getMessage());
      return;
    }
  }

}
