<?php

declare(strict_types = 1);

namespace Drupal\multilingual_migrate_example\Plugin\Derivative;

use Drupal\migrate\Plugin\Derivative\MigrateEntity;
use Drupal\multilingual_migrate_example\Plugin\migrate\destination\TranslatableEntity;

/**
 * Deriver for translatable_entity:ENTITY_TYPE entity migrations.
 */
class MigrateTranslatableEntity extends MigrateEntity {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) : array  {
    foreach ($this->entityDefinitions as $entity_type => $entity_info) {
      $this->derivatives[$entity_type] = [
        'id' => "translatable_entity:$entity_type",
        'class' => TranslatableEntity::class,
        'requirements_met' => 1,
        'provider' => $entity_info->getProvider(),
      ];
    }
    return $this->derivatives;
  }

}
