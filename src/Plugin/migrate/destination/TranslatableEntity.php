<?php

declare(strict_types = 1);

namespace Drupal\multilingual_migrate_example\Plugin\migrate\destination;

use Drupal\Core\Entity\EntityInterface;
use Drupal\migrate\Plugin\migrate\destination\Entity;
use Drupal\migrate\Plugin\migrate\destination\EntityContentBase;
use Drupal\migrate\Plugin\MigrateIdMapInterface;
use Drupal\migrate\Row;

/**
 * Provides a destination plugin for multilingual migrations.
 *
 * @MigrateDestination(
 *   id = "translatable_entity",
 *   deriver = "Drupal\multilingual_migrate_example\Plugin\Derivative\MigrateTranslatableEntity"
 * )
 */
class TranslatableEntity extends EntityContentBase {

  /**
   * {@inheritdoc}
   */
  public function isTranslationDestination() : bool {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function updateEntity(EntityInterface $entity, Row $row) : EntityInterface {
    $entity = parent::updateEntity($entity, $row);
    // Always delete on rollback, even if it's the default translation.
    $this->setRollbackAction($row->getIdMap(), MigrateIdMapInterface::ROLLBACK_DELETE);

    return $entity;
  }

  /**
   * {@inheritdoc}
   */
  public function rollback(array $destination_identifier) {
    // We want to delete the entity and all the translations so use
    // Entity:rollback because EntityContentBase::rollback will not remove the
    // default translation.
    Entity::rollback($destination_identifier);
  }

}
