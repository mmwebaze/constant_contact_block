<?php

namespace Drupal\constant_contact_block\Plugin\Block;

use Drupal\constant_contact_block\Form\ConstantContactForm;
use Drupal\constant_contact_block\services\ConstantContactDataInterface;
use Drupal\constant_contact_block\services\ConstantContactInterface;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'constant contact' block.
 *
 * @Block(
 *   id = "constant_contact",
 *   admin_label = @Translation("Constant Contact"),
 *   category = @Translation("Custom constant contact block")
 * )
 */
class ConstantContactBlock extends BlockBase implements BlockPluginInterface, ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\constant_contact_block\services\ConstantContactInterface
   */
  protected $constantContactManager;
  /**
   * @var \Drupal\constant_contact_block\services\ConstantContactDataInterface
   */
  protected $constantContactDataService;
  protected $contactLists;
  /**
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;
  private $lists = array();
  private $machineName;

  public function __construct(array $configuration, $plugin_id, $plugin_definition,
                              ConstantContactInterface $constantContactManager,
                              ConstantContactDataInterface $constantContactDataService, ConfigFactory $configFactory, Messenger $messenger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->constantContactManager = $constantContactManager;
    $this->constantContactDataService = $constantContactDataService;
    $this->messenger = $messenger;
    $this->machineName = $this->getMachineNameSuggestion();
    $constantContantConfigs = $configFactory->getEditable('constant_contact_block.constantcontantconfig');

    if ($constantContantConfigs->get('data_src')){
      $this->contactLists = $constantContactManager->getContactLists();
      $this->contactLists = json_decode($this->contactLists);
    }
    else{
      $this->contactLists = $constantContactDataService->getContactLists();
    }

  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition){
        return new static($configuration, $plugin_id, $plugin_definition,
          $container->get('constant_contact_block.manager_service'),
          $container->get('constant_contact_block.data_manager'),
          $container->get('config.factory'),
          $container->get('messenger')
        );
    }
    /**
     * {@inheritdoc}
     */
    public function blockForm($form, FormStateInterface $form_state) {
      $form = parent::blockForm($form, $form_state);
      $config = $this->getConfiguration();

      if (isset($config['machine_name'])){
        $this->machineName = $config['machine_name'];
      }

      $listOptions = [];
      foreach ($this->contactLists as $contactList){
        $listOptions[$contactList->id] = $contactList->name;
      }
      $this->contactLists = $listOptions;
      $form['first_name'] = array(

      );

      $emailLists = [];
      if (isset($config['cc_email_'.$this->machineName])){
        $emailLists = $config['cc_email_'.$this->machineName];
      }

      $form['cc_email_'.$this->machineName] = array(
        '#title' => $this->t('Email lists'),
        '#type' => 'checkboxes',
        '#multiple' => TRUE,
        '#description' => $this->t('Constant contact email lists available.'),
        '#options' => $listOptions,
        '#default_value' =>  $emailLists,
       // '#required' => TRUE,
      );

      return $form;
    }
    /**
     * {@inheritdoc}
     */
    public function build() {
      $config = $this->getConfiguration();
      $machineName = $config['machineName'];
      $constantContactForm = new ConstantContactForm($machineName, $config['constant_contact_block_form_'.$machineName], $this->messenger);
      // $form = $form = \Drupal::formBuilder()->getForm('Drupal\constant_contact_block_form_\Form\ConstantContactForm', $parameter);
      $form = $form = \Drupal::formBuilder()->getForm($constantContactForm);
      return $form;
    }
  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('machineName', $this->machineName);
    $selectedLists = $form_state->getValue('cc_email_'.$this->machineName);

    foreach ($selectedLists as $selectedList => $value){
      if ($value != 0){
        $this->lists[$value] = $this->contactLists[$value];
      }
    }

    $this->setConfigurationValue('cc_email_'.$this->machineName, $selectedLists);
    $this->setConfigurationValue('constant_contact_block_form_'.$this->machineName, $this->lists);
  }
}