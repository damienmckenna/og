<?php

namespace Drupal\Tests\og\Functional;

use Drupal\Core\Url;
use Drupal\entity_test\Entity\EntityTest;
use Drupal\node\Entity\Node;
use Drupal\og\Entity\OgMembership;
use Drupal\og\Entity\OgRole;
use Drupal\og\Og;
use Drupal\og\OgAccess;
use Drupal\og\OgMembershipInterface;
use Drupal\simpletest\ContentTypeCreationTrait;
use Drupal\simpletest\NodeCreationTrait;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests subscribe to group.
 *
 * @group og
 */
class GroupSubscribeTest extends BrowserTestBase {

  use ContentTypeCreationTrait;
  use NodeCreationTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = ['node', 'og'];

  /**
   * Test entity group.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  protected $group1;

  /**
   * Test normal user with no connection to the organic group.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $normalUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Create bundles.
    $this->groupBundle = Unicode::strtolower($this->randomMachineName());
    $this->nonGroupBundle = Unicode::strtolower($this->randomMachineName());

    // Define the entities as groups.
    Og::groupManager()->addGroup('node', $this->groupBundle);

    // Create node author user.
    $user = User::create(['name' => $this->randomString()]);
    $user->save();

    // Create group.
    $this->group1 = Node::create([
      'type' => $this->groupBundle,
      'title' => $this->randomString(),
      'uid' => $user->id(),
    ]);
    $this->group1->save();

    // Create non-group.
    $this->group2 = Node::create([
      'type' => $this->nonGroupBundle,
      'title' => $this->randomString(),
      'uid' => $user->id(),
    ]);
    $this->group2->save();

    // Create an unpublished node.
    $this->group3 = Node::create([
      'type' => $this->groupBundle,
      'title' => $this->randomString(),
      'uid' => $user->id(),
      'status' => NODE_NOT_PUBLISHED,
    ]);
    $this->group3->save();

    $this->user1 = User::create(['name' => $this->randomString()]);
    $this->user1->save();

  }

  /**
   * Tests 'update group' special permission.
   *
   * @dataProvider subscribeAccessProvider
   */
  public function testSubscribeAccess($entity_id, $code) {
    $this->drupalLogin($this->user1);
    $options = array(
      'entity_type' => 'node',
      'entity_id' => $entity_id,
    );

    $path = Url::fromRoute('og.subscribe', $options)->toString();
    $this->drupalGet($path);
    $this->assertSession()->statusCodeEquals($code);
  }

  public function subscribeAccessProvider() {
    return [
      [$this->group1->id(), 200],
      [$this->group2->id(), 403],
      [$this->group3->id(), 403],
    ];
  }


}
