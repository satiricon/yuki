<?php

namespace Drupal\yuki\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Config\Entity\ConfigEntityInterface;

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

  /**
   * @var integer
   */
  protected $weight;

  public function getRegexp(){

    return $this->regexp;
  }

  public function getWeight(){
    return $this->weight;
  }

  public function map($attribute_name, $path) {
    $matches = array();
    preg_match($this->getRegexp(), $path,$matches);

    dump($attribute_name, $path, $matches, $this->getRegexp());

    return $matches[$attribute_name];
  }

  /**
   * Sorts active blocks by weight; sorts inactive blocks by name.
   */
  public static function sort(ConfigEntityInterface $a, ConfigEntityInterface $b) {
    // Separate enabled from disabled.
    $status = (int) $b->status() - (int) $a->status();
    if ($status !== 0) {
      return $status;
    }

    // Sort by weight.
    $weight = $a->getWeight() - $b->getWeight();
    if ($weight) {
      return $weight;
    }

    // Sort by label.
    return strcmp($a->label(), $b->label());
  }

}