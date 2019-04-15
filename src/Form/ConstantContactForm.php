<?php

namespace Drupal\constant_contact_block\Form;

use Drupal\constant_contact_block\items\Contact;
use Drupal\constant_contact_block\items\EmailAddress;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\Messenger;
use Drupal\constant_contact_block\services\ConstantContactFieldsInterface;
use Drupal\constant_contact_block\services\ConstantContactInterface;

/**
 * Creates a form to create constant contact list on submission.
 *
 * @internal
 */
class ConstantContactForm extends FormBase {

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;
  /**
   * The constant contact field service.
   *
   * @var \Drupal\constant_contact_block\services\ConstantContactFieldsInterface
   */
  protected $constantContactFieldService;
  /**
   * The constant contact manager service.
   *
   * @var \Drupal\constant_contact_block\services\ConstantContactInterface
   */
  protected $constantContactManager;
  private $fields = [];
  /**
   * The form id.
   *
   * @var string
   */
  private $formId;

  /**
   * Constructs a new ConstantContactForm.
   *
   * @param string $formId
   *   The form id.
   * @param array $fields
   *   The fields to add to the form.
   * @param \Drupal\Core\Messenger\Messenger $messenger
   *   The messenger service.
   * @param \Drupal\constant_contact_block\services\ConstantContactFieldsInterface $constantContactFieldService
   *   The constantContactFieldService.
   * @param \Drupal\constant_contact_block\services\ConstantContactInterface $constantContactManager
   *   The constantContactManager service.
   */
  public function __construct($formId, array $fields, Messenger $messenger, ConstantContactFieldsInterface $constantContactFieldService, ConstantContactInterface $constantContactManager) {
    $this->formId = $formId;
    $this->fields = $fields;
    $this->messenger = $messenger;
    $this->constantContactFieldService = $constantContactFieldService;
    $this->constantContactManager = $constantContactManager;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'constant_contact_block_form_' . $this->formId;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $parameter = NULL) {
    $fields = $this->constantContactFieldService->loadFields();
    $selectedFields = $this->fields['fields'];

    foreach ($selectedFields as $selectedField) {
      $form[$selectedField] = [
        '#type' => $fields[$selectedField]['type'],
        '#title' => $fields[$selectedField]['title'],
        '#required' => $fields[$selectedField]['required'],
      ];
    }

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email:'),
      '#required' => TRUE,
    ];

    $form['email_lists'] = [
      '#type' => 'checkboxes',
      '#multiple' => TRUE,
      '#title' => t('Lists:'),
      '#options' => $this->fields['lists'],
      '#required' => TRUE,
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Register'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    $selectedLists = $form_state->getValue('email_lists');

    $lists = [];
    foreach ($selectedLists as $selectedList) {
      if ($selectedList != 0) {
        $listObj = new \stdClass();
        $listObj->id = $selectedList;
        array_push($lists, $listObj);
      }
    }
    $fields = $this->fields['fields'];
    $submittedFields = [];
    foreach ($fields as $field) {
      $submittedFields[$field] = $form_state->getValue($field);
    }

    $contact = new Contact($submittedFields['first_name'], $submittedFields['last_name'], $submittedFields['company_name'],
        'ACTIVE', [new EmailAddress($email)], $lists);

    $checkContact = $this->constantContactManager->checkContactExistsByEmail($email);
    $message = NULL;
    if (empty($checkContact)) {
      $message = $this->constantContactManager->addContact($contact);

    }
    else {
      $message = $this->constantContactManager->updateContant($checkContact, $lists);
    }
    if (count(json_decode($message))) {
      $this->messenger->addMessage('You have been added to the email lists');
    }
    else {
      $this->messenger->addMessage('Error adding you to email lists');
    }
  }

}
