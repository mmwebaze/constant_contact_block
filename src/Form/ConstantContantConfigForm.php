<?php

namespace Drupal\constant_contact_block\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Class ConstantContantConfigForm.
 */
class ConstantContantConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'constant_contact_block.constantcontantconfig',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'constant_contact_block_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('constant_contact_block.constantcontantconfig');
    $clientSecret = $config->get('client_secret');
    $redirectUri = $config->get('redirect_uri');
    $authReqUrl = $config->get('auth_request_url');
    $dataSrc = $config->get('data_src');
    $authToken = $config->get('auth_token');

    $form['constant_contact'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('General Constant Contact Settings'),
    );
    $form['constant_contact']['base_url'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Constant Contact base url'),
      '#default_value' => $config->get('base_url'),
      '#required' => TRUE,
    );
    $form['constant_contact']['api_key'] = array(
      '#type' => 'textfield', // to be changed to password
      '#title' => $this->t('Constant Contact api key'),
      '#default_value' => $config->get('api_key'),
      '#required' => TRUE,
    );
    $form['constant_contact']['client_secret'] = array(
      '#type' => 'textfield', // to be changed to password
      '#title' => $this->t('Client secret'),
      '#default_value' => isset($clientSecret) ? $clientSecret : '',
      '#required' => TRUE,
    );
    $form['constant_contact']['redirect_uri'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Redirect uri'),
      '#default_value' => isset($redirectUri) ? $redirectUri : $this->getRequest()->getSchemeAndHttpHost().'/constant_contact_block/getCode',
      //'#required' => TRUE,
    );
    $form['constant_contact']['auth_request_url'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Auth request url'),
      '#default_value' => isset($authReqUrl) ? $authReqUrl : '',
      //'#required' => TRUE,
    );
    $form['constant_contact']['auth_token'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Constant Contact Auth Token'),
      '#default_value' => isset($authToken) ? $authToken : '',
      //'#required' => TRUE,
    );

    $form['constant_contact']['data_src'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Source of contact lists'),
      '#default_value' => isset($dataSrc) ? $dataSrc : '1',
      '#options' => array(
        '0' => t('Local'),
        '1' => t('Remote'),
      ),
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

    $this->config('constant_contact_block.constantcontantconfig')
      ->set('base_url', $form_state->getValue('base_url'))
      ->set('api_key', $form_state->getValue('api_key'))
      ->set('client_secret', $form_state->getValue('client_secret'))
      ->set('redirect_uri', $form_state->getValue('redirect_uri'))
      ->set('auth_request_url', $form_state->getValue('auth_request_url'))
      ->set('auth_token', $form_state->getValue('auth_token'))
      ->set('data_src', $form_state->getValue('data_src'))
      ->save();
  }
}
