<?php

namespace Drupal\astrovia\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use VehicleTypeInterface;
use LocationInterface;

/**
 * Defines the Vehicle entity.
 *
 * @ingroup astrovia
 *
 * @ContentEntityType(
 *   id = "vehicle",
 *   label = @Translation("Vehicle"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\astrovia\VehicleListBuilder",
 *     "views_data" = "Drupal\astrovia\Entity\VehicleViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\astrovia\Form\VehicleForm",
 *       "add" = "Drupal\astrovia\Form\VehicleForm",
 *       "edit" = "Drupal\astrovia\Form\VehicleForm",
 *       "delete" = "Drupal\astrovia\Form\VehicleDeleteForm",
 *     },
 *     "access" = "Drupal\astrovia\VehicleAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\astrovia\VehicleHtmlRouteProvider",
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
  public function getVehicleType() {
    return $this->get('vehicle_type_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getVehicleTypeId() {
    return $this->get('vehicle_type_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setVehicleTypeId($id) {
    $this->set('vehicle_type_id', $id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setVehicleType(VehicleTypeInterface $vehicle_type) {
    $this->set('vehicle_type_id', $vehicle_type->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLocation() {
    return $this->get('location_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getLocationId() {
    return $this->get('location_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setLocationId($id) {
    $this->set('location_id', $id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setLocation(LocationInterface $location) {
    $this->set('location_id', $location->id());
    return $this;
  }

  public function getImage() {
    return $this->get('image')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setImage(FileInterface $image) {
    $this->set('image', $image);
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
        ->setRequired(TRUE)
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

    // The uid is an entity reference to the user entity type, which allows to access the user id with $node->uid->target_id
    // and the user entity with $node->uid->entity. NodeInterface also defines getAuthor() and getAuthorId(). (@todo: check owner vs. revisionAuthor)
    $fields['vehicle_type_id'] = BaseFieldDefinition::create('entity_reference')
        ->setLabel(t('Vehicle Type'))
        ->setDescription(t('The Vehicle Type of the associated Vehicle.'))
        ->setSetting('target_type', 'vehicle_type')
        ->setSetting('handler', 'default')
        ->setRequired(TRUE)
        ->setDisplayOptions('view', array(
            'label' => 'above',
            'type' => 'entity_reference_label',
            'weight' => -3,
        ))
        ->setDisplayOptions('form', array(
            'type' => 'entity_reference_autocomplete',
            'settings' => array(
            'match_operator' => 'CONTAINS',
            'size' => 60,
            'autocomplete_type' => 'tags',
            'placeholder' => '',
            ),
            'weight' => -3,
        ))
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

    $fields['location_id'] = BaseFieldDefinition::create('entity_reference')
        ->setLabel(t('Location'))
        ->setDescription(t('The current Location of the associated Vehicle.'))
        ->setSetting('target_type', 'location')
        ->setSetting('handler', 'default')
        ->setRequired(TRUE)
        ->setDisplayOptions('view', array(
            'label' => 'above',
            'type' => 'entity_reference_label',
            'weight' => -3,
        ))
        ->setDisplayOptions('form', array(
            'type' => 'entity_reference_autocomplete',
            'settings' => array(
            'match_operator' => 'CONTAINS',
            'size' => 60,
            'autocomplete_type' => 'tags',
            'placeholder' => '',
            ),
            'weight' => -3,
        ))
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
        ->setLabel(t('Naam'))
        ->setDescription(t('Naam van de Vehicle entity.'))
        ->setRequired(TRUE)
        ->setSettings([
            'max_length' => 50,
            'text_processing' => 0,
        ])
        ->setDefaultValue(NULL)
        ->setDisplayOptions('view', [
            'label' => 'above',
            'type' => 'string',
            'weight' => -8,
        ])
        ->setDisplayOptions('form', [
            'type' => 'string_textfield',
            'weight' => -8,
        ])
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

    $fields['description'] = BaseFieldDefinition::create('string_long')
        ->setLabel(t('Beschrijving'))
        ->setDescription(t('De beschrijving van de Vehicle entity.'))
        ->setSettings([
            'default_value' => '',
            'text_processing' => 0,
        ])
        ->setDefaultValue(NULL)
        ->setDisplayOptions('form', [
            'type' => 'string_textarea',
            'weight' => -7,
            'settings' => [
            'rows' => 4,
            ],
        ])
        ->setDisplayOptions('view', [
            'label' => 'above',
            'type' => 'string',
            'weight' => -7,
        ])
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

    $fields['price'] = BaseFieldDefinition::create('integer')
        ->setLabel(t('Prijs'))
        ->setDescription(t('De prijs van de Vehicle entity.'))
        ->setRequired(TRUE)
        ->setSettings([
            'min' => 1,
            'max' => 10000,
            'prefix' => '',
            'suffix' => ' Shanix'
        ])
        ->setDefaultValue(0)
        ->setDisplayOptions('view', [
            'label' => 'above',
            'type' => 'number_integer',
            'weight' => -5,
        ])
        ->setDisplayOptions('form', [
            'type' => 'number',
            'weight' => -5,
        ])
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

    $fields['seats'] = BaseFieldDefinition::create('integer')
        ->setLabel(t('Zitplaatsen'))
        ->setDescription(t('Hoeveelheid zitplaatsen van de Vehicle entity.'))
        ->setRequired(TRUE)
        ->setSettings([
            'min' => 1,
            'max' => 500,
            'prefix' => '',
            'suffix' => ' zitplaatsen'
        ])
        ->setDefaultValue(0)
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

    $fields['age'] = BaseFieldDefinition::create('integer')
        ->setLabel(t('Leeftijd'))
        ->setDescription(t('Leeftijd van de Vehicle entity.'))
        ->setSettings([
            'min' => 1,
            'max' => 500,
            'prefix' => '',
            'suffix' => ' miljoen jaar'
        ])
        ->setDefaultValue(0)
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

        $fields['image'] = BaseFieldDefinition::create('image')
        ->setLabel(t('Image'))
        ->setDescription(t('The image of the vehicle item.'))
        ->setRequired(TRUE)
        ->setDefaultValue(NULL)
        ->setDisplayOptions('view', [
            'type' => 'image',
            'weight' => 5,
            'label' => 'hidden',
        ])
        ->setDisplayOptions('form', [
            'type' => 'image',
            'weight' => -1,
        ])
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

    $fields['power'] = BaseFieldDefinition::create('integer')
        ->setLabel(t('Power'))
        ->setDescription(t('The power of your Vehicle.'))
        ->setRequired(FALSE)
        ->setSettings([
            'min' => 0,
            'max' => 5,
        ])
        ->setDefaultValue(0)
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

    $fields['speed'] = BaseFieldDefinition::create('integer')
        ->setLabel(t('Speed'))
        ->setDescription(t('The speed of your Vehicle.'))
        ->setRequired(FALSE)
        ->setSettings([
            'min' => 0,
            'max' => 5,
        ])
        ->setDefaultValue(0)
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

    $fields['accuracy'] = BaseFieldDefinition::create('integer')
        ->setLabel(t('Accuracy'))
        ->setDescription(t('The accuracy of your Vehicle.'))
        ->setRequired(FALSE)
        ->setSettings([
            'min' => 0,
            'max' => 5,
        ])
        ->setDefaultValue(0)
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

    $fields['status'] = BaseFieldDefinition::create('entity_reference')
        ->setLabel(t('Status'))
        ->setDescription(t('The status of the Vehicle'))
        ->setRevisionable(TRUE)
        ->setRequired(TRUE)
        ->setSetting('target_type', 'taxonomy_term')
        ->setSetting('handler', 'default:taxonomy_term')
        ->setSetting('handler_settings',
            [
                'target_bundles' => [
                    'vehicle_status' => 'vehicle_status'
                ]
            ]
        )
        ->setTranslatable(TRUE)
        ->setDisplayOptions('view', [
            'label' => 'hidden',
            'type' => 'string',
            'weight' => 1,
        ])
        ->setDisplayOptions('form', [
            'type' => 'options_select',
            'weight' => 1,
        ])
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

    /* $fields['status'] = BaseFieldDefinition::create('boolean')
        ->setLabel(t('Publishing status'))
        ->setDescription(t('A boolean indicating whether the Vehicle Type is published.'))
        ->setDefaultValue(TRUE)
        ->setDisplayOptions('form', [
            'type' => 'boolean_checkbox',
            'weight' => -3,
        ]); */

    $fields['created'] = BaseFieldDefinition::create('created')
        ->setLabel(t('Created'))
        ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
        ->setLabel(t('Changed'))
        ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
