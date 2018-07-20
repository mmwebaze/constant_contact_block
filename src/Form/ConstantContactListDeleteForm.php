<?php

namespace Drupal\constant_contact_block\Form;

use Drupal\constant_contact_block\services\ConstantContactDataInterface;
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
  protected $constantContactDataService;
  public function __construct(ConstantContactDataInterface $constantContactDataService) {
    $this->constantContactDataService = $constantContactDataService;
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
  public function buildForm(array $form, FormStateInterface $form_state, $listId = NULL){
    $this->listId = $listId;

    return parent::buildForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state){
    $this->constantContactDataService->deleteList($this->listId);
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

    return $this->t('Do you want to delete %listId', array('%listId' => $this->listId));
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('constant_contact_block.data_manager')
    );
  }
}