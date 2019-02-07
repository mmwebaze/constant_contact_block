<?php

namespace Drupal\constant_contact_block\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Reason entity.
 *
 * @ingroup constant_contact_block
 *
 * @ContentEntityType(
 *   id = "reason",
 *   label = @Translation("Reason"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\constant_contact_block\ReasonListBuilder",
 *     "views_data" = "Drupal\constant_contact_block\Entity\ReasonViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\constant_contact_block\Form\ReasonForm",
 *       "add" = "Drupal\constant_contact_block\Form\ReasonForm",
 *       "edit" = "Drupal\constant_contact_block\Form\ReasonForm",
 *       "delete" = "Drupal\constant_contact_block\Form\ReasonDeleteForm",
 *     },
 *     "access" = "Drupal\constant_contact_block\ReasonAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\constant_contact_block\ReasonHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "reason",
 *   admin_permission = "administer reason entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/config/constant_contact_block/reason/{reason}",
 *     "add-form" = "/admin/config/constant_contact_block/reason/add",
 *     "edit-form" = "/admin/config/constant_contact_block/reason/{reason}/edit",
 *     "delete-form" = "/admin/config/constant_contact_block/reason/{reason}/delete",
 *     "collection" = "/admin/config/constant_contact_block/reason",
 *   },
 *   field_ui_base_route = "reason.settings"
 * )
 */
class Reason extends ContentEntityBase implements ReasonInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }
  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Reason entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The reason for leaving the group list(s).'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['number_left'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Number Left'))
      ->setDescription(t('The number of users who have selected this reason for unsubscribing from a group list.'))
      ->setDefaultValue(0)
      ->setReadOnly(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ]);
      $fields['weight'] = BaseFieldDefinition::create('integer')
          ->setLabel(t('Weight'))
          ->setDescription(t('The weight of the reason that determines its position in a list.'))
          ->setDefaultValue(0)
          ->setReadOnly(TRUE)
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayOptions('form', [
              'type' => 'string_textfield',
              'weight' => -4,
      ]);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Reason can be selected by users unsubscribing from group lists.'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }
  public function setNumberLeft($numberLeft){
    $this->set('number_left', $numberLeft);
  }
  public function getNumberLeft(){
    return $this->get('number_left')->value;
  }
  public function setWeight($weight){
      $this->set('weight', $weight);
  }
  public function getWeight(){
      return $this->get('weight')->value;
  }
}
