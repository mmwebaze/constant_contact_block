<?php

namespace Drupal\constant_contact_block\Plugin\Block;

use Drupal\constant_contact_block\Form\ConstantContactForm;
use Drupal\Component\Uuid\Php;
use Drupal\constant_contact_block\services\ConstantContactInterface;
use Drupal\constant_contact_block\services\ConstantContactManager;
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
  protected $constantContactManager;
  protected $contactLists;
  private $uuid;
  private $lists = array();

  public function __construct(array $configuration, $plugin_id, $plugin_definition,
                              ConstantContactInterface $constantContactManager, Php $uuidService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->constantContactManager = $constantContactManager;
    $this->uuid = $uuidService->generate();
    $this->contactLists = $constantContactManager->getContactLists();
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition){
        return new static($configuration, $plugin_id, $plugin_definition,
          $container->get('constant_contact_block.manager_service'),
          $container->get('uuid')
        );
    }
    /**
     * {@inheritdoc}
     */
    public function blockForm($form, FormStateInterface $form_state) {
      $form = parent::blockForm($form, $form_state);
      $config = $this->getConfiguration();

      if (isset($config['uuid'])){
        $this->uuid = $config['uuid'];
      }

      $contactLists = json_decode($this->contactLists);


      $listOptions = [];
      foreach ($contactLists as $contactList){
        $listOptions[$contactList->id] = $contactList->name;
      }
      $this->contactLists = $listOptions;

      $emailLists = [];
      if (isset($config['cc_email_'.$this->uuid])){
        $emailLists = $config['cc_email_'.$this->uuid];
      }

      $form['cc_email_'.$this->uuid] = array(
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
      $uuid = $config['uuid'];
      $constantContactForm = new ConstantContactForm($uuid, $config['constant_contact_block_form_'.$uuid]);
      // $form = $form = \Drupal::formBuilder()->getForm('Drupal\constant_contact_block_form_\Form\ConstantContactForm', $parameter);
      $form = $form = \Drupal::formBuilder()->getForm($constantContactForm);
      return $form;
    }
  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('uuid', $this->uuid);
    $selectedLists = $form_state->getValue('cc_email_'.$this->uuid);
    foreach ($selectedLists as $selectedList => $value){
      if ($value != 0){
        $this->lists[$value] = $this->contactLists[$value];
      }
    }
    $this->setConfigurationValue('cc_email_'.$this->uuid, $selectedLists);
    $this->setConfigurationValue('constant_contact_block_form_'.$this->uuid, $this->lists);
    //$values = $form_state->getValue('cc_email_'.$this->uuid);

   print_r($this->lists);
   //print_r($selectedLists);
   //die('submit');
  }
 // private function
}