<?php

namespace Drupal\constant_contact_block\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for initiating importation of lists.
 */
class ConstantContactImportListForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'constant_contact_block_import_list_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $parameter = NULL) {
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import Lists'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirect('constant_contact_block.import_lists', ['importStatus' => 1]);
  }

}
