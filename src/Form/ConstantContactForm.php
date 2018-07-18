<?php

namespace Drupal\constant_contact_block\Form;

use Drupal\constant_contact_block\authentication\ConstantContactAuth2;
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
    //$cc = new ConstantContact('g2jnh338hrwqxtzkuhxzkrqt');
    //$contacts = $cc->contactService->getContacts('4f2f5ecd-0156-412e-bffc-cf95b4ce7958');
    $constantContactManger = \Drupal::service('constant_contact_block.manager_service');
    //$contactLists = $constantContactManger->getContactLists();
    //$contactLists = $constantContactManger->addContactList('Mitch');
    //die();
    $list1 = new \stdClass();
    $list1->id = '1124339648';
    $list2 = new \stdClass();
    $list2->id = '1993809041';
    $contact = new Contact('michael', 'mwebaze', '',
      'ACTIVE', [new EmailAddress('testing.again@gmail.com')], [$list1, $list2]);
    drupal_set_message(json_encode($contact));

    drupal_set_message($constantContactManger->addContact($contact));
  }
}