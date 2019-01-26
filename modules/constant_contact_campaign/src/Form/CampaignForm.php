<?php

namespace Drupal\constant_contact_campaign\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Campaign edit forms.
 *
 * @ingroup constant_contact_campaign
 */
class CampaignForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\constant_contact_campaign\Entity\Campaign */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;
    $form['status']['#prefix'] = '<div class="campaign_status">';
    $form['status']['#suffix'] = '</div>';

    $form['#attached']['library'][] = 'constant_contact_campaign/campaign_addition';
    $form['actions']['submit']['#value'] = 'Save campaign';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    $campaignStatus = $form_state->getValue('status')['value'];
    $message = $this->queueCampaign($campaignStatus, $entity);

    switch ($status) {
      case SAVED_NEW:
        \Drupal::service('messenger')->addMessage($this->t('Created the %label Campaign'.$message, [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        \Drupal::service('messenger')->addMessage($this->t('Saved the %label Campaign'.$message, [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.campaign.canonical', ['campaign' => $entity->id()]);
  }
  private function queueCampaign($campaignStatus, $entity){
    if ($campaignStatus == 1){
      $campaignManager = \Drupal::service('constant_contact_campaign.manager');
      $campaignManager->createEmailCampaign();

      return ' & campaign will be sent.';
    }
    return '.';
  }
}
