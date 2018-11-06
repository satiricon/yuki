<?php

namespace Drupal\yuki\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\yuki\Entity\MediaDirectory;

class PresetForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state)
  {
    $form = parent::form($form, $form_state);

    /** @var MediaDirectory $directory */
    $directory = $this->entity;

    /** @var Mapper $mapper */

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => 255,
      '#default_value' => $directory->label(),
      '#description' => $this->t('Name of the media directory.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $directory->id(),
      '#machine_name' => [
        'exists' => '\Drupal\products\Entity\Importer::load',
      ],
      '#disabled' => !$directory->isNew(),
    ];

    $form['template'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Template'),
      '#default_value' => $directory->get('template'),
      '#description' => $this->t('The template for the ffmpeg command.'),
      '#required' => TRUE,
    ];


    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var MediaDirectory $directory */
    $directory = $this->entity;
    $status = $directory->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Mapper.', [
          '%label' => $directory->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Mapper.', [
          '%label' => $directory->label(),
        ]));
    }
    $form_state->setRedirectUrl($directory->toUrl('collection'));
  }

}