<?php

namespace Drupal\yuki\Mapper;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Config\Entity\DraggableListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;

class MapperListBuilder extends DraggableListBuilder {

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

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);

    $this->entities = $this->load();

    foreach ($this->entities as $entity) {
      $row = $this->buildRow($entity);
      if (isset($row['data1'])) {
        $form[$this->entitiesKey][$entity->id()]['data1'] = ['#markup' => $row['data1']];
      }
      if (isset($row['mapper'])) {
        $form[$this->entitiesKey][$entity->id()]['mapper'] = ['#markup' => $row['mapper']];
      }
    }

    return $form;
  }



  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId()
  {
    return 'mapper_list_form';
  }
}