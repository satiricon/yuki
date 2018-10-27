<?php

namespace Drupal\yuki\Mapper\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\yuki\Entity\Mapper;
use Drupal\yuki\Entity\PathInfoMapper;
use Drupal\yuki\Plugin\Mapper\MapperManager;
use Symfony\Component\DependencyInjection\ContainerInterface;


class MapperForm extends EntityForm {


  /**
   * @var MapperManager
   */
  protected $mapperManager;

  /**
   * ImporterForm constructor.
   *
   * @param MapperManager $importerManager
   */
  public function __construct(MapperManager $mapperManager) {
    $this->mapperManager = $mapperManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('yuki.mapper_manager')
    );
  }



  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var Mapper $mapper */
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


    $definitions = $this->mapperManager->getDefinitions();
    $options = [];
    foreach ($definitions as $id => $definition) {
      $options[$id] = $definition['label'];
    }

    $form['plugin'] = [
      '#type' => 'select',
      '#title' => $this->t('Plugin'),
      '#default_value' => $mapper->getPluginId(),
      '#options' => $options,
      '#description' => $this->t('The plugin to be used with this importer.'),
      '#required' => TRUE,
    ];

    $form['data'] = [
      '#type' => 'textarea',
      '#default_value' => $mapper->getData() ? $mapper->getData() : '',
      '#title' => $this->t('Data'),
      '#description' => $this->t('The Data Necesary to resolve the fields'),
      '#required' => TRUE,
    ];

    $form['weight'] = [
      '#type' => 'weight',
      '#default_value' => $mapper->get('weight') ? $mapper->get('weight') : '',
      '#title' => $this->t('Weight'),
      '#required' => FALSE,
      '#delta'  => 999,
    ];

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