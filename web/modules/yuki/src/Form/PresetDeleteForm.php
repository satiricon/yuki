<?php

namespace Drupal\yuki\Form;

use Drupal\Core\Entity\EntityConfirmFormBase;

class PresetDeleteForm extends EntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete %name?', ['%name' => $this->entity->label()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.preset.collection');
  }

}