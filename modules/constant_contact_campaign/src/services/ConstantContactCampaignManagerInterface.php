<?php

namespace Drupal\constant_contact_campaign\services;


interface ConstantContactCampaignManagerInterface {
  public function createEmailCampaign();
  public function modifyEmailCampaign($campaignId);
  public function deleteEmailCampaign($campaignId);
  public function testSendEmailCampaign($campaignId);
}