<?php

namespace Drupal\yuki\Mapper;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

class TagMapperListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Mapper');
    $header['fields'] = $this->t('Fields');
    //$header['weight'] = $this->t('Weight');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['fields'] = $entity->get('regexp')->count();
    //$row['weight'] = $entity->get('weight');
    return $row + parent::buildRow($entity);
  }


}