<?php

namespace Drupal\constant_contact_block\Plugin\Block;

use Drupal\constant_contact_block\Form\ConstantContactForm;
use Drupal\constant_contact_block\services\ConstantContactDataInterface;
use Drupal\constant_contact_block\services\ConstantContactFieldsInterface;
use Drupal\constant_contact_block\services\ConstantContactInterface;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormBuilder;

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
   * The constantContactManager service.
   *
   * @var \Drupal\constant_contact_block\services\ConstantContactInterface
   */
  protected $constantContactManager;
  /**
   * The constantContactDataService.
   *
   * @var \Drupal\constant_contact_block\services\ConstantContactDataInterface
   */
  protected $constantContactDataService;
  /**
   * The constantContactFieldService.
   *
   * @var \Drupal\constant_contact_block\services\ConstantContactFieldsInterface
   */
  protected $constantContactFieldService;
  /**
   * The form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilder
   */
  protected $formBuilderService;
  protected $contactLists;
  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;
  private $lists = [];
  /**
   * The field machine name.
   *
   * @var string
   */
  private $machineName;

  /**
   * ConstantContactBlock constructor.
   *
   * @param array $configuration
   *   The plugin configuration.
   * @param string $plugin_id
   *   The plugin id.
   * @param mixed $plugin_definition
   *   Plugin definitions.
   * @param \Drupal\constant_contact_block\services\ConstantContactInterface $constantContactManager
   *   The constant contant manager service.
   * @param \Drupal\constant_contact_block\services\ConstantContactDataInterface $constantContactDataService
   *   The constant contanct data service.
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   *   The configuration object.
   * @param \Drupal\Core\Messenger\Messenger $messenger
   *   The messenger service.
   * @param \Drupal\constant_contact_block\services\ConstantContactFieldsInterface $constantContactFieldService
   *   The constantContactFieldService.
   * @param \Drupal\Core\Form\FormBuilder $formBuilderService
   *   The form builder service.
   */
  public function __construct(array $configuration,
  $plugin_id,
  $plugin_definition,
                                ConstantContactInterface $constantContactManager,
                                ConstantContactDataInterface $constantContactDataService,
  ConfigFactory $configFactory,
                                Messenger $messenger,
  ConstantContactFieldsInterface $constantContactFieldService,
  FormBuilder $formBuilderService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->constantContactManager = $constantContactManager;
    $this->constantContactDataService = $constantContactDataService;
    $this->messenger = $messenger;
    $this->machineName = $this->getMachineNameSuggestion();
    $this->formBuilderService = $formBuilderService;
    $this->constantContactFieldService = $constantContactFieldService;
    $constantContantConfigs = $configFactory->getEditable('constant_contact_block.constantcontantconfig');

    if ($constantContantConfigs->get('data_src')) {
      $this->contactLists = $constantContactManager->getContactLists();
      $this->contactLists = json_decode($this->contactLists);
    }
    else {
      $this->contactLists = $constantContactDataService->getContactLists();
    }

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition,
        $container->get('constant_contact_block.manager_service'),
        $container->get('constant_contact_block.data_manager'),
        $container->get('config.factory'),
        $container->get('messenger'),
        $container->get('constant_contact_block.fields_manager'),
        $container->get('form_builder')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $fields = $this->constantContactFieldService->loadFields();
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();

    if (isset($config['machine_name'])) {
      $this->machineName = $config['machine_name'];
    }

    $listOptions = [];
    foreach ($this->contactLists as $contactList) {
      $listOptions[$contactList->id] = $contactList->name;
    }
    $this->contactLists = $listOptions;

    $emailLists = [];

    if (isset($config['cc_email_' . $this->machineName])) {
      $emailLists = $config['cc_email_' . $this->machineName];
    }

    $selectedFields = [];
    if (isset($config['cc_fields_' . $this->machineName])) {
      $selectedFields = $config['cc_fields_' . $this->machineName];
    }

    foreach ($fields as $field => $value) {
      $form[$field] = [
        '#title' => $value['title'],
        '#type' => 'checkbox',
        '#default_value' => in_array($field, $selectedFields) ? 1 : 0,
      ];
    }

    $form['cc_email_' . $this->machineName] = [
      '#title' => $this->t('Email lists'),
      '#type' => 'checkboxes',
      '#multiple' => TRUE,
      '#description' => $this->t('Constant contact email lists available.'),
      '#options' => $listOptions,
      '#default_value' => $emailLists,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    $machineName = $config['machineName'];
    $constantContactForm = new ConstantContactForm($machineName, $config['constant_contact_block_form_' . $machineName], $this->messenger, $this->constantContactFieldService, $this->constantContactManager);
    $form = $form = $this->formBuilderService->getForm($constantContactForm);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $fields = $this->constantContactFieldService->loadFields();
    $selectedFields = [];
    foreach ($fields as $field => $value) {
      $selectedField = $form_state->getValue($field);

      if ($selectedField == 1) {
        array_push($selectedFields, $field);
      }
    }

    $this->setConfigurationValue('machineName', $this->machineName);
    $selectedLists = $form_state->getValue('cc_email_' . $this->machineName);

    foreach ($selectedLists as $selectedList => $value) {
      if ($value != 0) {
        $this->lists[$value] = $this->contactLists[$value];
      }
    }
    $this->setConfigurationValue('cc_fields_' . $this->machineName, $selectedFields);
    $this->setConfigurationValue('cc_email_' . $this->machineName, $selectedLists);
    $this->setConfigurationValue('constant_contact_block_form_' . $this->machineName, ['lists' => $this->lists, 'fields' => $selectedFields]);
  }

}
