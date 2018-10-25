<?php

namespace Drupal\constant_contact_campaign\services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Messenger\Messenger;

class ConstantContactCampaignManager implements ConstantContactCampaignManagerInterface {
  protected $client;
  protected $messenger;
  protected $configFactory;
  private $token;
  private $baseUrl;
  private $apiKey;
  private $header;

  public function __construct(Client $client, ConfigFactory $configFactory, Messenger $messenger){
    $this->client = $client;
    $this->messenger = $messenger;
    $constantContactconfigFactory = $configFactory->getEditable('constant_contact_block.constantcontantconfig');
    $this->token = $constantContactconfigFactory->get('auth_token');
    $this->baseUrl = $constantContactconfigFactory->get('base_url');
    $this->apiKey = $constantContactconfigFactory->get('api_key');
    $this->header = ['headers' => [
      'Authorization' => 'Bearer ' .$this->token,
      'Content-Type' => 'application/json',
      'Accept' => 'application/json'
    ]];
    $this->configFactory = $configFactory->getEditable('constant_contact_campaign.campaign_config');
  }
  public function createEmailCampaign(){
    //$endPoint = '/emailmarketing/campaignscontacts?api_key='.$this->apiKey;
    try{
      die('I have created a campaign: '.$this->configFactory->get('from_name'));
    }
    catch(RequestException $e){
      $this->messenger->addMessage($e->getMessage());
      return $this;
    }
  }
  public function modifyEmailCampaign($campaignId){

  }
  public function deleteEmailCampaign($campaignId){

  }
  public function testSendEmailCampaign($campaignId){
    $testEndpoint = '/emailmarketing/campaigns/'.$campaignId.'/tests';
  }
}