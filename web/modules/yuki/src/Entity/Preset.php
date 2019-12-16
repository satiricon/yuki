<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 11/6/18
 * Time: 12:29 PM
 */

namespace Drupal\yuki\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Mapper Config entity.
 *
 * @ConfigEntityType(
 *   id = "preset",
 *   label = @Translation("FFmpeg Preset"),
 *   handlers = {
 *     "list_builder" = "Drupal\yuki\ListBuilder\PresetListBuilder",
 *     "form" = {
 *       "add" = "Drupal\yuki\Form\PresetForm",
 *       "edit" = "Drupal\yuki\Form\PresetForm",
 *       "delete" = "Drupal\yuki\Form\PresetDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "preset",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   links = {
 *      "add-form"    = "/admin/structure/preset/add",
 *      "edit-form"   = "/admin/structure/preset/{preset}/edit",
 *      "delete-form" = "/admin/structure/preset/{preset}/delete",
 *      "collection"  = "/admin/structure/preset"
 *   }
 * )
 */
class Preset extends ConfigEntityBase implements PresetInterface {

  /**
   * The Preset ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Preset label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Preset template command (ffmpg options).
   *
   * @var string
   */
  protected $template;


  /**
   * The values to add into the template
   * normally input and output paths
   *
   * @param array $values
   *
   * @return mixed
   */
  public function setConfigurationValues(array $values) {
    $template = $this->get('template');

    array_walk($values, function($i, $k) use(&$template){
      $template = str_replace("%$k%", $i, $template);
    } );

    $this->template = $template;
  }

  /**
   * Returns the command in array form ready to be executed by a symfony process
   *
   * @return array
   */
  public function getCommand() {

    return json_decode($this->template);
  }
}
