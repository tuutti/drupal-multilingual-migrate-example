<?php

declare(strict_types = 1);

namespace Drupal\multilingual_migrate_example\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;

/**
 * Source plugin for retrieving data from multilingual source.
 *
 * @MigrateSource(
 *   id = "example_multilingual_source"
 * )
 */
final class ExampleMultilingualSource extends SourcePluginBase {

  /**
   * {@inheritdoc}
   */
  public function fields() : array {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return 'ExampleMultilingualSource';
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'id' => ['type' => 'string'],
      'language' => ['type' => 'string'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function initializeIterator() : \Generator {
    yield ['id' => 1, 'language' => 'en', 'name' => 'test 1 en'];
    yield ['id' => 1, 'language' => 'da', 'name' => 'test 1 da'];
    yield ['id' => 2, 'language' => 'da', 'name' => 'test 2 da'];
    yield ['id' => 2, 'language' => 'en', 'name' => 'test 2 en'];
  }

}
