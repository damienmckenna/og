<?php

namespace Drupal\og\Plugin\OgAdmin;

use Drupal\og\OgAdminRouteAbstract;

/**
 * Manage people in the group.
 *
 * @OgAdmin(
 *   id = "members",
 *   path = "members",
 *   title = @Translation("Members"),
 *   description = @Translation("Manage the group's members")
 * )
 */
class Members extends OgAdminRouteAbstract {

  /**
   * {@inheritdoc}
   */
  public function getParentRoute() {

    return [
      'controller' => '\Drupal\og\Controller\OgAdminMembersController::membersList',
      'title' => 'Members',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getSubRoutes() {

    return [
      'add' => [
        'controller' => '\Drupal\og\Controller\OgAdminMembersController::addPeopleForm',
        'sub_path' => 'add',
        'title' => 'Add people',
        'type' => 'local_action',
      ],
    ];
  }

}