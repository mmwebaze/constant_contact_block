<?php

namespace Drupal\constant_contact_campaign\services;


class ConstantContactCampaignManager implements ConstantContactCampaignManagerInterface {
  public function createEmailCampaign(){

  }
  public function modifyEmailCampaign($campaignId){

  }
  public function deleteEmailCampaign($campaignId){

  }
  public function testSendEmailCampaign($campaignId){
    $testEndpoint = '/emailmarketing/campaigns/'.$campaignId.'/tests';
  }
}