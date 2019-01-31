<?php

namespace Drupal\constant_contact_block\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactory;

class ConstantContactUnsubscribeForm extends FormBase {
  private $contact;

  /**
   * @var \Drupal\Core\Config\Config|\Drupal\Core\Config\ImmutableConfig
   */
  protected $configFactory;
  public function __construct(ConfigFactory $configFactory) {
    $this->configFactory = $configFactory->getEditable('constant_contact_block.constantcontantconfig');
  }
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'constant_contact_block_unsubscribe_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $contactId = NULL) {
    $title = $this->configFactory->get('title');
    $unsubscribeMessage = $this->configFactory->get('message');

    $form['unsubscribe_link'] = array(
      '#type' => 'markup',
      '#markup' => '<div>'.$unsubscribeMessage.'</div>'
    );

    $form['unsubscribe_reasons'] = array(
      '#type' => 'radios',
      '#title' => $this->t($title),
      '#options' => array(
        0 => 'I no longer want to receive these emails',
        1 => 'I never signed up for this mailing list',
        2 => 'The emails are inappropriate',
        3 => 'The emails are spam and should be reported',
        4 => 'Other (fill in reason below)'
      ),
      '#required' => TRUE,
      '#prefix' => '<div class="unsubscribe">',
      '#suffix' => '</div>',
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Unsubscribe'),
      '#button_type' => 'primary',
    );
    $form['#attached']['library'][] = 'constant_contact_block/cc_block_unsubscribe';

    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get( 'config.factory')
    );
  }
}