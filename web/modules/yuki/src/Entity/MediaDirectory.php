<?php

namespace Drupal\yuki\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Class MediaDirectory
 * @ConfigEntityType(
 *   id = "media_directory",
 *   label = @Translation("Media Directory"),
 *   handlers = {
 *     "list_builder" = "Drupal\yuki\ListBuilder\DirectoryListBuilder",
 *     "form" = {
 *       "add" = "Drupal\yuki\Form\DirectoryForm",
 *       "edit" = "Drupal\yuki\Form\DirectoryForm",
 *       "delete" = "Drupal\yuki\Form\DirectoryDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "media_directory",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   links = {
 *      "add-form"    = "/admin/structure/media-dir/add",
 *      "edit-form"   = "/admin/structure/media-dir/{media_directory}/edit",
 *      "delete-form" = "/admin/structure/media-dir/{media_directory}/delete",
 *      "collection"  = "/admin/structure/media-dir"
 *   }
 * )
 *
 */
class MediaDirectory extends ConfigEntityBase {

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
   * A media directory to scan and mantain
   *
   * @var string
   */
  protected $directory;


  /**
   * @return string
   */
  public function getDirectory(){

    return $this->directory;
  }


}