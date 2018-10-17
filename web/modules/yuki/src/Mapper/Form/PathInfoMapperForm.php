<?php

namespace Drupal\yuki\Mapper\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\yuki\Entity\PathInfoMapper;

class PathInfoMapperForm extends EntityForm {



  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var PathInfoMapper $mapper */
    $mapper = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => 255,
      '#default_value' => $mapper->label(),
      '#description' => $this->t('Name of the Importer.'),
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

    $form['regexp'] = [
      '#type' => 'textarea',
      '#default_value' => $mapper->getRegexp() ? $mapper->getRegexp() : '',
      '#title' => $this->t('Regexp'),
      '#description' => $this->t('The Named Regexp to resolve the data'),
      '#required' => TRUE,
    ];

    $form['weight'] = array(
      '#type' => 'weight',
      '#title' => $this
        ->t('Weight'),
      '#default_value' => $mapper->get('weight'),
      '#delta' => 9999,
    );

    /*$definitions = $this->importerManager->getDefinitions();
    $options = [];
    foreach ($definitions as $id => $definition) {
      $options[$id] = $definition['label'];
    }*/

    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var PathInfoMapper $mapper */
    $mapper = $this->entity;
    $status = $mapper->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Mapper.', [
          '%label' => $mapper->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Mapper.', [
          '%label' => $mapper->label(),
        ]));
    }
    $form_state->setRedirectUrl($mapper->toUrl('collection'));
  }


}