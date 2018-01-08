<?php

/**
 * @file
 * Contains ResteasyRestfulEntityArtistsResource__0_1.
 */


/**
 * Implements RestfulEntityBaseNode class for the "artist" content type.
 */
class RestfulEntityVehiclesResource extends RestfulEntityBaseNode {

  /**
   * Overrides RestfulEntityBaseNode::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['price'] = array(
      'property' => 'field_prijs',
    );

    return $public_fields;
  }
}