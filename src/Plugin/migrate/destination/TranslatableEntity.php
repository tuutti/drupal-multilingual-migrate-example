<?php

declare(strict_types = 1);

namespace Drupal\multilingual_migrate_example\Plugin\migrate\destination;

use Drupal\Core\Entity\EntityInterface;
use Drupal\migrate\MigrateException;
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
  public function updateEntity(EntityInterface $entity, Row $row) {
    $entity = parent::updateEntity($entity, $row);
    // Always delete on rollback, even if it's "default" translation.
    $this->setRollbackAction($row->getIdMap(), MigrateIdMapInterface::ROLLBACK_DELETE);

    return $entity;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEntity(Row $row, array $old_destination_id_values) {
    if (!$langcode = $row->getSourceProperty('language')) {
      throw new MigrateException('Missing "language" source property.');
    }

    $entityId = reset($old_destination_id_values) ?: $this->getEntityId($row);

    if (empty($entityId) || (!$entity = $this->storage->load($entityId))) {
      // Attempt to ensure we always have a bundle.
      if ($bundle = $this->getBundle($row)) {
        $row->setDestinationProperty($this->getKey('bundle'), $bundle);
      }
      $row->setDestinationProperty($this->getKey('langcode'), $langcode);

      // Stubs might need some required fields filled in.
      if ($row->isStub()) {
        $this->processStubRow($row);
      }
      $entity = $this->storage->create($row->getDestination());
      $entity->enforceIsNew();
    }

    if ($entity->hasTranslation($langcode)) {
      // Update existing translation.
      return $this->updateEntity($entity->getTranslation($langcode), $row);
    }
    // Stubs might need some required fields filled in.
    if ($row->isStub()) {
      $this->processStubRow($row);
    }
    return $entity->addTranslation($langcode, $row->getDestination());
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      $this->getKey('id') => ['type' => 'string'],
    ];
  }

}
