<?php

namespace Drupal\constant_contact_block\Form;

use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\constant_contact_block\services\ConstantContactInterface;

class ConstantContactUnsubscribeForm extends FormBase {
  private $reasons;
  /**
   * @var \Drupal\constant_contact_block\services\ConstantContactInterface
   */
  protected $constantContactService;

  /**
   * @var \Drupal\Core\Config\Config|\Drupal\Core\Config\ImmutableConfig
   */
  protected $configFactory;
  public function __construct(ConfigFactory $configFactory, ConstantContactInterface $constantContactService) {
    $this->configFactory = $configFactory->getEditable('constant_contact_block.constantcontantconfig');
    $this->constantContactService = $constantContactService;
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
    $unsubscribeReasons = $this->configFactory->get('reasons');

    $this->reasons = explode('|', $unsubscribeReasons);
    array_push($this->reasons, 'Other (fill in reason below)');

    $form['#prefix'] = '<div class="constant-contact-block-form-wrapper">';
    $form['#suffix'] = '</div>';
    $form['unsubscribe_link'] = array(
      '#type' => 'markup',
      '#markup' => '<div>'.$unsubscribeMessage.'</div>'
    );
    $form['contact_id'] = array(
      '#type' => 'value',
      '#value' => $contactId,
    );
    $form['unsubscribe_reasons'] = array(
      '#type' => 'radios',
      '#title' => $this->t($title),
      '#options' => $this->reasons,
      '#required' => TRUE,
      '#prefix' => '<div class="unsubscribe"></div>',
      '#suffix' => '<div id="cc_block_reason"> </div>',
      '#ajax' => array(
        'callback' => '::otherReason',
        'event' => 'change',
      ),
    );

    $form['other_reason'] = array(
      '#type' => 'textarea',
      '#prefix' => '<div class="other_reason">',
      '#suffix' => '</div>',
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Unsubscribe'),
      '#button_type' => 'primary',
      /*'#ajax' => array(
        'callback' => '::processUnsubscribe',
        'wrapper' => 'constant-contact-block-form-wrapper',
        'event' => 'click',
        'progress' => array(
          'type' => 'throbber',
          'message' => $this->t('Processing ...'),
        ),
      ),*/
    );
    $form['#attached']['library'][] = 'constant_contact_block/cc_block_unsubscribe';

    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $contactId = $form_state->getValue('contact_id');
    $this->constantContactService->deleteContact($contactId);
  }
  public function otherReason(array &$form, FormStateInterface $form_state){
    $ajaxResponse = new AjaxResponse();
    $selectedReason = $form_state->getValue('unsubscribe_reasons');
    $keys = array_keys($this->reasons);
    $last = end($keys);

    if ($selectedReason == $last){
      $textArea = "<textarea rows=\"4\" cols=\"50\" name=\"other_reason\" required=\"required\"></textarea>";
      $ajaxResponse->addCommand(new CssCommand('.other_reason', ['display' => 'block']));
      $ajaxResponse->addCommand(new HtmlCommand('.other_reason .form-textarea-wrapper', $textArea));
    }
    else{
      $ajaxResponse->addCommand(new InvokeCommand('.other_reason .form-textarea-wrapper textarea', 'remove', ['textarea']));
    }

    return $ajaxResponse;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get( 'config.factory'),
      $container->get('constant_contact_block.manager_service')
    );
  }
}