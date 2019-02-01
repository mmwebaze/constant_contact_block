<?php

namespace Drupal\constant_contact_block\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\constant_contact_block\services\ConstantContactInterface;
use Drupal\constant_contact_block\services\ConstantContactDataInterface;

class ConstantContactIndividualListsForm extends FormBase {
  /**
   * @var \Drupal\constant_contact_block\services\ConstantContactInterface
   */
  protected $constantContactService;

  /**
   * @var \Drupal\constant_contact_block\services\ConstantContactDataInterface
   */
  protected $constantContactDataService;
  private $contact;
  public function __construct(ConstantContactInterface $constantContactService, ConstantContactDataInterface $constantContactDataService){
    $this->constantContactService = $constantContactService;
    $this->constantContactDataService = $constantContactDataService;
  }
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'constant_contact_block_individual_lists_form_';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $contactId = NULL) {
    $contact = $this->constantContactService->getContactById($contactId);
    $this->contact = json_decode($contact);

    $email = $this->contact->email_addresses[0]->email_address;

    $lists = array();
    foreach ($this->contact->lists as $list){

      if ($list->status == 'ACTIVE'){
        $result = $this->constantContactDataService->getContactList($list->id);
        $listName = $result[0]->name;

        if (empty($result)){
          $result = $this->constantContactService->getContactList($list->id);
          $listName = json_decode($result)->name;
        }
        $lists[$list->id] = $listName;
      }
    }
    $unsubscribelink = '/account/unsubscribe/'. $contactId;
    $form['unsubscribe_link'] = array(
      '#type' => 'markup',
      '#markup' => '<a href='.$unsubscribelink.'>Unsubscribe here</a>'
    );
    $form['employee_mail'] = array(
      '#type' => 'email',
      '#title' => t('Email:'),
      '#required' => TRUE,
      '#default_value' => $email,
    );
    $form['email_lists'] = array(
      '#type' => 'checkboxes',
      '#multiple' => TRUE,
      '#title' => t('Email Lists I am in:'),
      '#options' => $lists,
      '#required' => TRUE,
      '#default_value' => array_keys($lists),
      '#prefix' => '<div>',
      '#suffix' => '</div>',
    );

    /*$form['subscribe_me'] = array(
      '#type' => 'radios',
      '#title' => t('Unsubscribe me'),
      '#options' => array(
        'ACTIVE' => 'I\'d like to continue receiving communications from the selected lists.',
        'REMOVED' => 'I\'d like to be removed from all lists I am subscribed to.'
      ),
      '#required' => TRUE,
      '#default_value' => $this->contact->status,
      '#prefix' => '<div>',
      '#suffix' => '</div>',
    );

    $form['#attached']['library'][] = 'constant_contact_block/cc_block_settings';*/

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save Settings'),
      '#button_type' => 'primary',
    );

    return $form;
  }
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $emailListValues = array_values($form_state->getValue('email_lists'));

    if(array_sum($emailListValues) == 0){
      $form_state->setError($form, $this->t('At least one email list has to be selected.'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $selectedLists = $form_state->getValue('email_lists');

    $lists = [];
    foreach ($selectedLists as $selectedList){
      if ($selectedList != 0){
        $listObj = new \stdClass();
        $listObj->id = $selectedList;
        array_push($lists, $listObj);
      }
    }
    $this->constantContactService->updateContant($this->contact, $lists, TRUE);
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('constant_contact_block.manager_service'),
      $container->get('constant_contact_block.data_manager')
    );
  }
}