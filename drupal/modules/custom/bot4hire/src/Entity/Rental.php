<?php

namespace Drupal\bot4hire\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Rental entity.
 *
 * @ingroup bot4hire
 *
 * @ContentEntityType(
 *   id = "rental",
 *   label = @Translation("Rental"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\bot4hire\RentalListBuilder",
 *     "views_data" = "Drupal\bot4hire\Entity\RentalViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\bot4hire\Form\RentalForm",
 *       "add" = "Drupal\bot4hire\Form\RentalForm",
 *       "edit" = "Drupal\bot4hire\Form\RentalForm",
 *       "delete" = "Drupal\bot4hire\Form\RentalDeleteForm",
 *     },
 *     "access" = "Drupal\bot4hire\RentalAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\bot4hire\RentalHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "rental",
 *   admin_permission = "administer rental entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/rental/{rental}",
 *     "add-form" = "/admin/structure/rental/add",
 *     "edit-form" = "/admin/structure/rental/{rental}/edit",
 *     "delete-form" = "/admin/structure/rental/{rental}/delete",
 *     "collection" = "/admin/structure/rental",
 *   },
 *   field_ui_base_route = "rental.settings"
 * )
 */
class Rental extends ContentEntityBase implements RentalInterface {

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
  public function getStartDate() {
    return $this->get('start_date')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setStartDate($start_date) {
    $this->set('start_date', $start_date);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEndDate() {
    return $this->get('end_date')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEndDate($end_date) {
    $this->set('end_date', $end_date);
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
      ->setDescription(t('The user ID of author of the Rental entity.'))
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
      ->setDescription(t('The name of the Rental entity.'))
      ->setSettings([
        'max_length' => 50,
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
	  ->setDisplayConfigurable('view', TRUE);

	$fields['vehicle_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Voertuig ID'))
      ->setDescription(t('De voertuig ID van de Vehicle Entity die overeen komt met de Review entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'vehicle')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'vehicle',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
	  ->setDisplayConfigurable('view', TRUE);
	  
	  $fields['start_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Start Uitleenperiode'))
      ->setDescription(t('De startdatum van de Rental entity.'))
	  ->setSettings([
		'datetime_type' => 'date'
	  ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
		'label' => 'above',
		'type' => 'datetime_default',
		'settings' => [
		  'format_type' => 'medium',
		],
		'weight' => 14,
	  ])
      ->setDisplayOptions('form', [
		'type' => 'datetime_default',
		'weight' => 14,
	  ])
      ->setDisplayConfigurable('form', TRUE)
	  ->setDisplayConfigurable('view', TRUE);

	  $fields['end_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Einde Uitleenperiode'))
      ->setDescription(t('De einddatum van de Rental entity.'))
	  ->setSettings([
		'datetime_type' => 'date'
	  ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
		'label' => 'above',
		'type' => 'datetime_default',
		'settings' => [
		  'format_type' => 'medium',
		],
		'weight' => 14,
	  ])
      ->setDisplayOptions('form', [
		'type' => 'datetime_default',
		'weight' => 14,
	  ])
      ->setDisplayConfigurable('form', TRUE)
	  ->setDisplayConfigurable('view', TRUE);


    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Rental is published.'))
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
