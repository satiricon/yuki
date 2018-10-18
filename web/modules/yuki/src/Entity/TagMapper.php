<?php

namespace Drupal\yuki\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\yuki\Mapper\DataMapperInterface;

/**
 * Defines the Importer entity.
 *
 * @ConfigEntityType(
 *   id = "tag_mapper",
 *   label = @Translation("Tag Mapper"),
 *   handlers = {
 *     "list_builder" = "Drupal\yuki\Mapper\TagMapperListBuilder",
 *     "form" = {
 *       "add" = "Drupal\yuki\Mapper\Form\TagMapperForm",
 *       "edit" = "Drupal\yuki\Mapper\Form\TagMapperForm",
 *       "delete" = "Drupal\yuki\Mapper\Form\MapperDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "tag_mapper",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/tag/mapper/add",
 *     "edit-form" = "/admin/structure/tag/mapper/{tag_mapper}/edit",
 *     "delete-form" = "/admin/structure/tag/mapper/{tag_mapper}/delete",
 *     "collection" = "/admin/structure/tag/mapper"
 *   }
 * )
 */
class TagMapper extends ConfigEntityBase implements  DataMapperInterface {

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


  protected $fields;


  public function map($attribute_name, $data)
  {
    // TODO: Implement map() method.
  }

}