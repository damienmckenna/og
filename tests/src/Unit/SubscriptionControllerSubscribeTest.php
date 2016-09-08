<?php

namespace Drupal\Tests\og\Unit;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityFormBuilderInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\og\Controller\SubscriptionController;
use Drupal\og\MembershipManagerInterface;
use Drupal\og\OgAccessInterface;
use Drupal\og\OgMembershipInterface;
use Drupal\og\OgMembershipTypeInterface;
use Drupal\Tests\UnitTestCase;
use Drupal\user\EntityOwnerInterface;

/**
 * Tests the subscribe method from the subscription controller.
 *
 * @group og
 * @coversDefaultClass \Drupal\og\Controller\SubscriptionController
 */
class SubscriptionControllerSubscribeTest extends UnitTestCase {

  /**
   * The entity type ID.
   *
   * @var string
   */
  protected $entityTypeId;

  /**
   * The group entity.
   *
   * @var \Drupal\Core\Entity\ContentEntityInterface|\Prophecy\Prophecy\ObjectProphecy
   */
  protected $group;

  /**
   * The membership type entity.
   *
   * @var \Drupal\og\OgMembershipTypeInterface|\Prophecy\Prophecy\ObjectProphecy
   */
  protected $membershipType;

  /**
   * OG access service.
   *
   * @var \Drupal\og\OgAccessInterface|\Prophecy\Prophecy\ObjectProphecy
   */
  protected $ogAccess;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    $this->ogAccess = $this->prophesize(OgAccessInterface::class);
    $this->entityTypeId = $this->randomMachineName();
    $this->group = $this->prophesize(ContentEntityInterface::class);
    $this->membershipType = $this->prophesize(OgMembershipTypeInterface::class);
  }

  /**
   * Tests non-member trying to unsubscribe from group.
   *
   * @covers ::subscribe
   * @expectedException \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   */
  public function testNotValidEntity() {
    $group = $this->prophesize(EntityInterface::class);

    $controller = new SubscriptionController($this->ogAccess->reveal());
    $controller->subscribe($this->entityTypeId, $group->reveal(), $this->membershipType->reveal());
  }
}

//// @todo Delete after https://www.drupal.org/node/1858196 is in.
//namespace Drupal\og\Controller;
//
//if (!function_exists('drupal_set_message')) {
//
//  /**
//   * Mocking for drupal_set_message().
//   */
//  function drupal_set_message() {
//  }
//
//}
