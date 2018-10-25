<?php

namespace Drupal\constant_contact_campaign\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ConstantContactCampaignConfigForm extends ConfigFormBase{
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'constant_contact_campaign.campaign_config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'constant_contact_campaign_config_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('constant_contact_campaign.campaign_config');
    $fromName = $config->get('from_name');
    $address = $config->get('address_line_1');
    $city = $config->get('city');
    $state = $config->get('state');
    $postCode = $config->get('postal_code');
    $country = $config->get('country');
    $reminderText = $config->get('permission_reminder_text');

    $form['constant_contact_campaign'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('General Constant Contact Campaign Settings'),
    );
    $form['constant_contact_campaign']['from_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Organization name'),
      '#default_value' => isset($fromName) ? $fromName : '',
      //'#required' => TRUE,
    );
    $form['constant_contact_campaign']['address_line_1'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Address'),
      '#default_value' => isset($address) ? $address : '',
      //'#required' => TRUE,
    );
    $form['constant_contact_campaign']['city'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => isset($city) ? $city : '',
      //'#required' => TRUE,
    );
    $form['constant_contact_campaign']['state'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('State'),
      '#default_value' => isset($state) ? $state : '',
      //'#required' => TRUE,
    );
    $form['constant_contact_campaign']['postal_code'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Postal code'),
      '#default_value' => isset($postCode) ? $postCode : '',
      //'#required' => TRUE,
    );
    $form['constant_contact_campaign']['country'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#default_value' => isset($country) ? $country : '',
      //'#required' => TRUE,
    );
    $form['constant_contact_campaign']['from_email'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('From email (defaults to email set on site)'),
      '#default_value' => $config->get('from_email'),
      '#required' => TRUE,
    );
    $form['constant_contact_campaign']['reply_to_email'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Reply to email (defaults to email set on site)'),
      '#default_value' => $config->get('reply_to_email'),
      '#required' => TRUE,
    );
    $form['constant_contact_campaign']['permission_reminder_text'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Permission reminder text'),
      '#rows' => 5,
      '#cols' => 12,
      '#default_value' => isset($reminderText) ? $reminderText : 'As a reminder, you\'re receiving this email because 
      you have expressed an interest in MyCompany. Don\'t forget to add from_email@example.com 
      to your address book so we\'ll be sure to land in your inbox! You may unsubscribe 
      if you no longer wish to receive our emails.',
      '#resizable' => FALSE,
      //'#required' => TRUE,
    );

    return parent::buildForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('constant_contact_campaign.campaign_config')
      ->set('from_name', $form_state->getValue('from_name'))
      ->set('address_line_1', $form_state->getValue('address_line_1'))
      ->set('city', $form_state->getValue('city'))
      ->set('state', $form_state->getValue('state'))
      ->set('postal_code', $form_state->getValue('postal_code'))
      ->set('from_email', $form_state->getValue('from_email'))
      ->set('reply_to_email', $form_state->getValue('reply_to_email'))
      ->set('permission_reminder_text', $form_state->getValue('permission_reminder_text'))
      ->save();
  }
}