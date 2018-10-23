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
      '#type' => 'checkboxes',
      '#multiple' => TRUE,
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
    $constantContactManger = \Drupal::service('constant_contact_block.manager_service');

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

    $checkContact = $constantContactManger->checkContactExistsByEmail($email);

    if (empty($checkContact)){
      $constantContactManger->addContact($contact);
    }
    else{
      $constantContactManger->updateContant($checkContact, $lists);
    }
  }
}