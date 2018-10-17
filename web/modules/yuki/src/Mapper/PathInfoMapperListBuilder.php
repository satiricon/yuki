<?php

namespace Drupal\yuki\Mapper;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

class PathInfoMapperListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Mapper');
    $header['regexp'] = $this->t('Regexp');
    $header['weight'] = $this->t('Weight');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['regexp'] = $entity->get('regexp');
    $row['weight'] = $entity->get('weight');
    return $row + parent::buildRow($entity);
  }
}