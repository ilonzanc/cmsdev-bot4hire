<?php

/**
 * @file
 * Vehicles plugin definition.
 */

$plugin = array(
  'label' => t('Vehicles'),
  'resource' => 'vehicles',
  'name' => 'vehicles__0_1',
  'entity_type' => 'node',
  'bundle' => 'vehicle',
  'description' => t('This resource presents vehicles from REST easy.'),
  'class' => 'ResteasyRestfulEntityVehiclesResource',
  'major_version' => 0,
  'minor_version' => 1,
);