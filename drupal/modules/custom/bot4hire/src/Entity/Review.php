<?php

namespace Drupal\bot4hire\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Review entity.
 *
 * @ingroup bot4hire
 *
 * @ContentEntityType(
 *   id = "review",
 *   label = @Translation("Review"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\bot4hire\ReviewListBuilder",
 *     "views_data" = "Drupal\bot4hire\Entity\ReviewViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\bot4hire\Form\ReviewForm",
 *       "add" = "Drupal\bot4hire\Form\ReviewForm",
 *       "edit" = "Drupal\bot4hire\Form\ReviewForm",
 *       "delete" = "Drupal\bot4hire\Form\ReviewDeleteForm",
 *     },
 *     "access" = "Drupal\bot4hire\ReviewAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\bot4hire\ReviewHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "review",
 *   admin_permission = "administer review entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/review/{review}",
 *     "add-form" = "/admin/structure/review/add",
 *     "edit-form" = "/admin/structure/review/{review}/edit",
 *     "delete-form" = "/admin/structure/review/{review}/delete",
 *     "collection" = "/admin/structure/review",
 *   },
 *   field_ui_base_route = "review.settings"
 * )
 */
class Review extends ContentEntityBase implements ReviewInterface {

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
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('title', $title);
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
      ->setDescription(t('De user ID van de auteur van de Review entity.'))
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

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Titel'))
      ->setDescription(t('De titel van de Review entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -3,
      ])
      ->setDisplayConfigurable('form', TRUE)
	  ->setDisplayConfigurable('view', TRUE);

	$fields['body'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Body'))
      ->setDescription(t('De body van de Review entity.'))
	  ->setSettings([
		'default_value' => '',
		'text_processing' => 0,
	  ])
	  ->setDisplayOptions('form', [
		'type' => 'text_textarea_with_summary',
		'settings' => [
		  'rows' => 4,
		],
		'weight' => -2,
	  ])
	  ->setDisplayOptions('view', [
		'type' => 'text_summary_or_trimmed',
		'settings' => [
		  'trim_length' => 600,
		],
		'weight' => -2,
	  ])
	  ->setDisplayConfigurable('form', TRUE)
	  ->setDisplayConfigurable('view', TRUE);
	  
	  $fields['rating'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Rating'))
      ->setDescription(t('Rating van de Vehicle entity.'))
      ->setSettings([
        'min' => 1,
		'max' => 5,
		'prefix' => '',
		'suffix' => '/5'
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'number_integer',
        'weight' => -1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -1,
      ])
      ->setDisplayConfigurable('form', TRUE)
	  ->setDisplayConfigurable('view', TRUE); 

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
