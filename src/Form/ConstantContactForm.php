<?php

namespace Drupal\constant_contact_block\Form;

use Drupal\constant_contact_block\items\Contact;
use Drupal\constant_contact_block\items\EmailAddress;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ConstantContactForm extends FormBase{

  private $fields = array();
  private $formId;
  public function __construct($formId, array $fields) {
    $this->formId = $formId;
    $this->fields = $fields;
    drupal_set_message(json_encode($fields).'**');
  }
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'constant_contact_block_form_'.$this->formId;
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $parameter = NULL) {

    $form['employee_mail'] = array(
      '#type' => 'email',
      '#title' => t('Email:'),
      '#required' => TRUE,
    );
    $form['email_lists'] = array(
      '#title' => $this->t('Email lists'),
      '#type' => 'checkboxes',
      '#multiple' => TRUE,
      '#description' => t('Constant contact email lists available.'),
      '#options' => $this->fields,
      '#required' => TRUE,
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Register'),
      '#button_type' => 'primary',
    );
    return $form;

  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('employee_mail');
    $selectedLists = $form_state->getValue('email_lists');
    //print_r($selectedLists);die();
    $constantContactManger = \Drupal::service('constant_contact_block.manager_service');
    //$contactLists = $constantContactManger->getContactLists();
    //$contactLists = $constantContactManger->addContactList('Open source');
    /*$db = \Drupal::service('constant_contact_block.data_manager');
    $db->addContactList($contactLists);*/
    //drupal_set_message($contactLists);//die('created');
    $lists = [];
    foreach ($selectedLists as $selectedList){
      if ($selectedList != 0){
        $listObj = new \stdClass();
        $listObj->id = $selectedList;
        array_push($lists, $listObj);
      }
    }

    $contact = new Contact('', '', '',
      'ACTIVE', [new EmailAddress($email)], $lists);
    drupal_set_message(json_encode($contact));

    drupal_set_message($constantContactManger->addContact($contact));
  }
}