<?php

namespace Drupal\yuki\Mapper;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

class MapperListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Mapper');
    $header['data1'] = $this->t('Data');
    $header['mapper'] = $this->t('Mapper');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['data1'] = $entity->get('data');
    $row['mapper'] = $entity->getPluginId();

    return $row + parent::buildRow($entity);
  }
}