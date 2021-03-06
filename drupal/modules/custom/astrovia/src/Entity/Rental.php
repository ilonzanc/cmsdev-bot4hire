<?php

namespace Drupal\astrovia\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use VehicleInterface;

/**
 * Defines the Rental entity.
 *
 * @ingroup astrovia
 *
 * @ContentEntityType(
 *   id = "rental",
 *   label = @Translation("Rental"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\astrovia\RentalListBuilder",
 *     "views_data" = "Drupal\astrovia\Entity\RentalViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\astrovia\Form\RentalForm",
 *       "add" = "Drupal\astrovia\Form\RentalForm",
 *       "edit" = "Drupal\astrovia\Form\RentalForm",
 *       "delete" = "Drupal\astrovia\Form\RentalDeleteForm",
 *     },
 *     "access" = "Drupal\astrovia\RentalAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\astrovia\RentalHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "rental",
 *   admin_permission = "administer rental entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "renter_id",
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
        'renter_id' => \Drupal::currentUser()->id(),
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
        return $this->get('renter_id')->entity;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwnerId() {
        return $this->get('renter_id')->target_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwnerId($uid) {
        $this->set('renter_id', $uid);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwner(UserInterface $account) {
        $this->set('renter_id', $account->id());
        return $this;
    }

    // Relation Vehicle Entity

    /**
     * {@inheritdoc}
     */
    public function getVehicle() {
        return $this->get('vehicle_id')->entity;
    }

    /**
     * {@inheritdoc}
     */
    public function getVehicleId() {
        return $this->get('vehicle_id')->target_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setVehicleId($id) {
        $this->set('vehicle_id', $id);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setVehicle(VehicleInterface $vehicle) {
        $this->set('vehicle_id', $vehicle->id());
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

    //TODO Relation Pickup Location Entity

    //TODO Relation Dropoff Location Entity

    /**
     * {@inheritdoc}
     */
    public function isAcceptedByOwner() {
        return (bool) $this->getEntityKey('accepted_by_owner');
    }

    /**
     * {@inheritdoc}
     */
    public function setAcceptedByOwner($accepted) {
        $this->set('accepted_by_owner', $accepted ? TRUE : FALSE);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isReturnedByRenter() {
        return (bool) $this->getEntityKey('returned_by_renter');
    }

    /**
     * {@inheritdoc}
     */
    public function setReturnedByRenter($returned) {
        $this->set('returned_by_renter', $returned ? TRUE : FALSE);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getReturnedTime() {
        return $this->get('returned_on')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setReturnedTime($timestamp) {
        $this->set('returned_on', $timestamp);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isReturnConfirmedByOwner() {
        return (bool) $this->getEntityKey('return_confirmed_by_owner');
    }

    /**
     * {@inheritdoc}
     */
    public function setReturnConfirmedByOwner($returnConfirm) {
        $this->set('return_confirmed_by_owner', $returnConfirm ? TRUE : FALSE);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
        $fields = parent::baseFieldDefinitions($entity_type);

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
                'weight' => 0,
            ])
            ->setDisplayOptions('form', [
                'type' => 'string_textfield',
                'weight' => 0,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);

        $fields['renter_id'] = BaseFieldDefinition::create('entity_reference')
            ->setLabel(t('Rented by'))
            ->setDescription(t('The user ID of author of the Rental entity.'))
            ->setRevisionable(TRUE)
            ->setSetting('target_type', 'user')
            ->setSetting('handler', 'default')
            ->setTranslatable(TRUE)
            ->setDisplayOptions('view', [
                'label' => 'hidden',
                'type' => 'author',
                'weight' => 1,
            ])
            ->setDisplayOptions('form', [
                'type' => 'entity_reference_autocomplete',
                'weight' => 1,
                'settings' => [
                'match_operator' => 'CONTAINS',
                'size' => '60',
                'autocomplete_type' => 'tags',
                'placeholder' => '',
                ],
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);

        $fields['vehicle_id'] = BaseFieldDefinition::create('entity_reference')
            ->setLabel(t('vehicle ID'))
            ->setDescription(t('The vehicle ID of the Vehicle Entity associated with the Review entity.'))
            ->setRevisionable(TRUE)
            ->setSetting('target_type', 'vehicle')
            ->setSetting('handler', 'default')
            ->setTranslatable(TRUE)
            ->setDisplayOptions('view', [
                'label' => 'hidden',
                'type' => 'string',
                'weight' => 2,
            ])
            ->setDisplayOptions('form', [
                'type' => 'entity_reference_autocomplete',
                'weight' => 2,
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
            ->setLabel(t('Start Rentaltime'))
            ->setDescription(t('The startdate of the Rental entity.'))
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
                'weight' => 3,
            ])
            ->setDisplayOptions('form', [
                'type' => 'datetime_default',
                'weight' => 3,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);

        $fields['end_date'] = BaseFieldDefinition::create('datetime')
            ->setLabel(t('End Rentaltime'))
            ->setDescription(t('The enddate of the Rental entity.'))
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
                'weight' => 4,
            ])
            ->setDisplayOptions('form', [
                'type' => 'datetime_default',
                'weight' => 4,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);

        $fields['accepted_by_owner'] = BaseFieldDefinition::create('boolean')
            ->setLabel(t('Rental accepted by owner'))
            ->setDescription(t('A boolean indicating whether the Rental is accepted by the Owner of the Vehicle.'))
            ->setDefaultValue(FALSE)
            ->setDisplayOptions('form', [
                'type' => 'boolean_checkbox',
                'weight' => 5,
            ]);

        $fields['returned_by_renter'] = BaseFieldDefinition::create('boolean')
            ->setLabel(t('Vehicle returned by renter'))
            ->setDescription(t('A boolean indicating whether the Vehicle was returned by the Renter.'))
            ->setDefaultValue(FALSE)
            ->setDisplayOptions('form', [
                'type' => 'boolean_checkbox',
                'weight' => 6,
            ]);

        $fields['return_date'] = BaseFieldDefinition::create('datetime')
            ->setLabel(t('Date of return'))
            ->setDescription(t('The return date of the Vehicle'))
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
                'weight' => 4,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);

        $fields['return_confirmed_by_owner'] = BaseFieldDefinition::create('boolean')
            ->setLabel(t('Return confirmed by'))
            ->setDescription(t('A boolean indicating whether the return is confirmed by the Owner of the Vehicle.'))
            ->setDefaultValue(FALSE)
            ->setDisplayOptions('form', [
                'type' => 'boolean_checkbox',
                'weight' => 7,
            ])
            ->setDisplayOptions('view', [
                'label' => 'above',
                'type' => 'string',
                'weight' => 0,
            ]);

        $fields['status'] = BaseFieldDefinition::create('boolean')
            ->setLabel(t('Publishing status'))
            ->setDescription(t('A boolean indicating whether the Review is published.'))
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
