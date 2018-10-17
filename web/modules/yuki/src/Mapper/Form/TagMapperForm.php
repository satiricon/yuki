<?php

namespace Drupal\yuki\Mapper\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\yuki\Entity\PathInfoMapper;

class TagMapperForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state)
  {
    $form = parent::form($form, $form_state);

    /** @var PathInfoMapper $mapper */
    $mapper = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => 255,
      '#default_value' => $mapper->label(),
      '#description' => $this->t('Name of tag.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $mapper->id(),
      '#machine_name' => [
        'exists' => '\Drupal\products\Entity\Importer::load',
      ],
      '#disabled' => !$mapper->isNew(),
    ];

    $form['fields'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Fields'),
      '#maxlength' => 255,
      '#default_value' => $mapper->label(),
      '#description' => $this->t('Name of tag source fields.'),
    ];

    return $form;

  }


}