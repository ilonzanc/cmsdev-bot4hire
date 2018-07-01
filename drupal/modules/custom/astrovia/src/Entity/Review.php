<?php

namespace Drupal\astrovia\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Review entity.
 *
 * @ingroup astrovia
 *
 * @ContentEntityType(
 *   id = "review",
 *   label = @Translation("Review"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\astrovia\ReviewListBuilder",
 *     "views_data" = "Drupal\astrovia\Entity\ReviewViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\astrovia\Form\ReviewForm",
 *       "add" = "Drupal\astrovia\Form\ReviewForm",
 *       "edit" = "Drupal\astrovia\Form\ReviewForm",
 *       "delete" = "Drupal\astrovia\Form\ReviewDeleteForm",
 *     },
 *     "access" = "Drupal\astrovia\ReviewAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\astrovia\ReviewHtmlRouteProvider",
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
        'reviewer_id' => \Drupal::currentUser()->id(),
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
    public function getBody() {
        return $this->get('body')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body) {
        $this->set('body', $body);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRating() {
        return $this->get('rating')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setRating($rating) {
        $this->set('rating', $rating);
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
        return $this->get('reviewer_id')->entity;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwnerId() {
        return $this->get('reviewer_id')->target_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwnerId($uid) {
        $this->set('reviewer_id', $uid);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwner(UserInterface $account) {
        $this->set('reviewer_id', $account->id());
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

        $fields['reviewer_id'] = BaseFieldDefinition::create('entity_reference')
        ->setLabel(t('Reviewer'))
        ->setDescription(t('The user ID of the Review Entity author.'))
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
            'weight' => 0,
            'settings' => [
            'match_operator' => 'CONTAINS',
            'size' => '60',
            'autocomplete_type' => 'tags',
            'placeholder' => '',
            ],
        ])
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

        $fields['review_type'] = BaseFieldDefinition::create('entity_reference')
            ->setLabel(t('Type of Review'))
            ->setDescription(t('The type of the current Reciew'))
            ->setRevisionable(TRUE)
            ->setSetting('target_type', 'taxonomy_term')
            ->setSetting('handler', 'default:taxonomy_term')
            ->setSetting('handler_settings',
                [
                    'target_bundles' => [
                        'review_types' => 'review_types'
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

        $fields['owner_id'] = BaseFieldDefinition::create('entity_reference')
        ->setLabel(t('Owner ID'))
        ->setDescription(t('The user ID of the owner that matches the current Review Entity.'))
        ->setRevisionable(TRUE)
        ->setSetting('target_type', 'user')
        ->setSetting('handler', 'default')
        ->setTranslatable(TRUE)
        ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 2,
        ])
        ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 2,
        'settings' => [
            'match_operator' => 'CONTAINS',
            'size' => '60',
            'autocomplete_type' => 'tags',
            'placeholder' => '',
        ],
        ])
        ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    $fields['renter_id'] = BaseFieldDefinition::create('entity_reference')
        ->setLabel(t('Renter ID'))
        ->setDescription(t('The user ID of the renter that matches the current Review Entity.'))
        ->setRevisionable(TRUE)
        ->setSetting('target_type', 'user')
        ->setSetting('handler', 'default')
        ->setTranslatable(TRUE)
        ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 3,
        ])
        ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 3,
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
        ->setLabel(t('Vehicle ID'))
        ->setDescription(t('De vehicle ID of the Vehicle Entity that matches the current Review entity.'))
        ->setRevisionable(TRUE)
        ->setSetting('target_type', 'vehicle')
        ->setSetting('handler', 'default')
        ->setTranslatable(TRUE)
        ->setDisplayOptions('view', [
            'label' => 'hidden',
            'type' => 'string',
            'weight' => 4,
        ])
        ->setDisplayOptions('form', [
            'type' => 'entity_reference_autocomplete',
            'weight' => 4,
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
        ->setDescription(t('The title of the Review entity.'))
        ->setSettings([
            'max_length' => 50,
            'text_processing' => 0,
        ])
        ->setDefaultValue('')
        ->setDisplayOptions('view', [
            'label' => 'above',
            'type' => 'string',
            'weight' => 5,
        ])
        ->setDisplayOptions('form', [
            'type' => 'string_textfield',
            'weight' => 5,
        ])
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);


        $fields['rating'] = BaseFieldDefinition::create('integer')
        ->setLabel(t('Rating'))
        ->setDescription(t('The rating of the Review entity.'))
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
            'weight' => 6,
        ])
        ->setDisplayOptions('form', [
            'type' => 'number',
            'weight' => 6,
        ])
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

        $fields['body'] = BaseFieldDefinition::create('string_long')
        ->setLabel(t('Body'))
        ->setDescription(t('The body of the Review entity.'))
        ->setSettings([
            'default_value' => '',
            'text_processing' => 0,
        ])
        ->setDisplayOptions('form', [
            'type' => 'string_textarea',
            'weight' => 7,
            'settings' => [
            'rows' => 4,
            ],
        ])
        ->setDisplayOptions('view', [
            'label' => 'above',
            'type' => 'string',
            'weight' => 7,
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
