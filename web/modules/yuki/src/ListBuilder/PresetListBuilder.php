<?php
namespace Drupal\yuki\ListBuilder;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

class PresetListBuilder extends ConfigEntityListBuilder
{
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Label');
    $header['template'] = $this->t('Template');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['template'] = $entity->get('template');

    return $row + parent::buildRow($entity);
  }
}