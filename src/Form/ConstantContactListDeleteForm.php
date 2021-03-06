<?php

namespace Drupal\constant_contact_block\Form;

use Drupal\constant_contact_block\services\ConstantContactDataInterface;
use Drupal\constant_contact_block\services\ConstantContactInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting Organisation unit entities.
 *
 * @ingroup constant_contact_block
 */
class ConstantContactListDeleteForm extends ConfirmFormBase {

  protected $listId;
  protected $listName;

  /**
   * @var \Drupal\constant_contact_block\services\ConstantContactDataInterface
   */
  protected $constantContactDataService;
  /**
   * @var \Drupal\constant_contact_block\services\ConstantContactInterface
   */
  protected $constantContactService;

  /**
   * ConstantContactListDeleteForm constructor.
   *
   * @param \Drupal\constant_contact_block\services\ConstantContactDataInterface $constantContactDataService
   * @param \Drupal\constant_contact_block\services\ConstantContactInterface $constantContactService
   */
  public function __construct(ConstantContactDataInterface $constantContactDataService,
                              ConstantContactInterface $constantContactService) {
    $this->constantContactDataService = $constantContactDataService;
    $this->constantContactService = $constantContactService;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'contact_list_delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $listId = NULL, $listName = NULL){
    $this->listId = $listId;
    $this->listName = $listName;

    $form['#attached']['library'][] = 'constant_contact_block/list_delete';
    $form['alert'] = array(
      '#type' => 'markup',
      '#markup' => '<div class="alert"><strong>Warning!</strong> Deletes the above list both locally and remotely.</div>'
    );

    return parent::buildForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state){
    $this->constantContactDataService->deleteList($this->listId);
    $this->constantContactService->deleteContactList($this->listId);

    $form_state->setRedirect('constant_contact_block.view_lists');
  }
  /**
   * {@inheritdoc}
   */
  public function getCancelUrl(){
    return new Url('constant_contact_block.view_lists');
  }
  /**
   * {@inheritdoc}
   */
  public function getQuestion(){

    return $this->t('Do you want to delete list \'%listName\' with ID %listId', array('%listName' => $this->listName,'%listId' => $this->listId));
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('constant_contact_block.data_manager'),
      $container->get('constant_contact_block.manager_service')
    );
  }
}