<?php

namespace Drupal\bot4hire\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Vehicle entity.
 *
 * @ingroup bot4hire
 *
 * @ContentEntityType(
 *   id = "vehicle",
 *   label = @Translation("Vehicle"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\bot4hire\VehicleListBuilder",
 *     "views_data" = "Drupal\bot4hire\Entity\VehicleViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\bot4hire\Form\VehicleForm",
 *       "add" = "Drupal\bot4hire\Form\VehicleForm",
 *       "edit" = "Drupal\bot4hire\Form\VehicleForm",
 *       "delete" = "Drupal\bot4hire\Form\VehicleDeleteForm",
 *     },
 *     "access" = "Drupal\bot4hire\VehicleAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\bot4hire\VehicleHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "vehicle",
 *   admin_permission = "administer vehicle entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/vehicle/{vehicle}",
 *     "add-form" = "/admin/structure/vehicle/add",
 *     "edit-form" = "/admin/structure/vehicle/{vehicle}/edit",
 *     "delete-form" = "/admin/structure/vehicle/{vehicle}/delete",
 *     "collection" = "/admin/structure/vehicle",
 *   },
 *   field_ui_base_route = "vehicle.settings"
 * )
 */
class Vehicle extends ContentEntityBase implements VehicleInterface {

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
  public function getVehicleType() {
    return $this->get('vehicle_type')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setVehicleType($vehicle_type) {
    $this->set('vehicle_type', $vehicle_type);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPrice() {
    return $this->get('price')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPrice($price) {
    $this->set('price', $price);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSeats() {
    return $this->get('seats')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSeats($seats) {
    $this->set('seats', $seats);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAge() {
    return $this->get('age')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setAge($age) {
    $this->set('age', $age);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPickupLocation() {
    return $this->get('pickup_location')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPickupLocation($pickup_location) {
    $this->set('pickup_location', $pickup_location);
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
      ->setLabel(t('Aangemaakt door'))
      ->setDescription(t('De user ID van de auteur van de Vehicle Entity.'))
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
      ->setLabel(t('Naam'))
      ->setDescription(t('Naam van de Vehicle entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -6,
      ])
      ->setDisplayConfigurable('form', TRUE)
	  ->setDisplayConfigurable('view', TRUE);

	  $fields['vehicle_type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Type Voertuig'))
      ->setDescription(t('Het type voertuig van de Vehicle Entity.'))
      ->setRevisionable(TRUE)
	  ->setSetting('target_type', 'taxonomy_term')
	  ->setSetting('handler', 'default:taxonomy_term')
      ->setSetting('handler_settings',
	  [
		'target_bundles' => [
		  'type' => 'type'
		]
	  ]
	)
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
    	'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
	  ->setDisplayConfigurable('view', TRUE);
	  
	$fields['price'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Prijs'))
      ->setDescription(t('De prijs van de Vehicle entity.'))
      ->setSettings([
        'min' => 1,
		'max' => 10000,
		'prefix' => '',
		'suffix' => ' Shanix'
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'number_integer',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
	  ->setDisplayConfigurable('view', TRUE); 

	$fields['seats'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Zitplaatsen'))
      ->setDescription(t('Hoeveelheid zitplaatsen van de Vehicle entity.'))
      ->setSettings([
        'min' => 1,
		'max' => 500,
		'prefix' => '',
		'suffix' => ' zitplaatsen'
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'number_integer',
        'weight' => -3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -3,
      ])
      ->setDisplayConfigurable('form', TRUE)
	  ->setDisplayConfigurable('view', TRUE); 

	$fields['age'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Leeftijd'))
      ->setDescription(t('Leeftijd van de Vehicle entity.'))
      ->setSettings([
        'min' => 1,
		'max' => 500,
		'prefix' => '',
		'suffix' => ' miljoen jaar'
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'number_integer',
        'weight' => -2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -2,
      ])
      ->setDisplayConfigurable('form', TRUE)
	  ->setDisplayConfigurable('view', TRUE); 

	  $fields['pickup_location'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Ophaallocatie'))
      ->setDescription(t('De ophaallocatie van de Vehicle Entity.'))
      ->setRevisionable(TRUE)
	  ->setSetting('target_type', 'taxonomy_term')
	  ->setSetting('handler', 'default:taxonomy_term')
      ->setSetting('handler_settings',
	  [
		'target_bundles' => [
		  'places' => 'places'
		]
	  ]
	)
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
    	'weight' => -1,
      ])
      ->setDisplayConfigurable('form', TRUE)
	  ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Vehicle is published.'))
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
