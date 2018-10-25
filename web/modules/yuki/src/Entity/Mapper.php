<?php

namespace Drupal\yuki\Entity;


use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Mapper Config entity.
 *
 * @ConfigEntityType(
 *   id = "mapper",
 *   label = @Translation("Mapper"),
 *   handlers = {
 *     "list_builder" = "Drupal\yuki\Mapper\MapperListBuilder",
 *     "form" = {
 *       "add" = "Drupal\yuki\Mapper\Form\MapperForm",
 *       "edit" = "Drupal\yuki\Mapper\Form\MapperForm",
 *       "delete" = "Drupal\yuki\Mapper\Form\MapperDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "mapper",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "weight" = "weight",
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/mapper/add",
 *     "edit-form" = "/admin/structure/mapper/{mapper}/edit",
 *     "delete-form" = "/admin/structure/mapper/{mapper}/delete",
 *     "collection" = "/admin/structure/mapper"
 *   }
 * )
 */
class Mapper extends ConfigEntityBase implements MapperInterface {

  /**
   * The Mapper ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Mapper label.
   *
   * @var string
   */
  protected $label;

  /**
   * The plugin ID of the plugin to be used for processing this mapping.
   *
   * @var string
   */
  protected $plugin;


  /**
   * The relevant data necesary to map Regexps in the case of a PathMapper
   *
   * @var string
   */
  protected $data;

  /**
   * @var integer
   */
  protected $weight;

  /**
   * @return string
   */
  public function getPluginId()
  {
    return $this->plugin;
  }

  /**
   * @return mixed
   */
  public function map($attribute_name, $data)
  {
    // TODO: Implement map() method.
    //aca devuelvo el resultado del map del plugin
  }

  /**
   * @return mixed
   */
  public function getData()
  {
    return $this->data;
  }
}
