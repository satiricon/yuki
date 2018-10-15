<?php

namespace Drupal\yuki\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Importer entity.
 *
 * @ConfigEntityType(
 *   id = "mapper",
 *   label = @Translation("Path Info Mapper"),
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
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/mapper/add",
 *     "edit-form" = "/admin/structure/mapper/{mapper}/edit",
 *     "delete-form" = "/admin/structure/mapper/{mapper}/delete",
 *     "collection" = "/admin/structure/mapper"
 *   }
 * )
 */
class PathInfoMapper extends ConfigEntityBase {

  /**
   * The Importer ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Importer label.
   *
   * @var string
   */
  protected $label;

  /**
   * Regexp to be aplied to the file uri
   * Has to use named patterns
   *
   * @var string
   */
  protected $regexp;

  public function getRegexp(){

    return $this->regexp;
  }

}