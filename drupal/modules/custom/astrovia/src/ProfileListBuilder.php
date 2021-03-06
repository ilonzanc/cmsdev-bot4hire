<?php

namespace Drupal\astrovia;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Profile entities.
 *
 * @ingroup astrovia
 */
class ProfileListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Profile ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\astrovia\Entity\Profile */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.profile.edit_form',
      ['profile' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
