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

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Campaign.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Campaign.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.campaign.canonical', ['campaign' => $entity->id()]);
  }

}
