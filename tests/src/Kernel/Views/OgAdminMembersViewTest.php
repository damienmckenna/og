<?php

namespace Drupal\Tests\og\Kernel\Views;

use Drupal\Component\Utility\Xss;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\og\Og;
use Drupal\Tests\views\Kernel\ViewsKernelTestBase;
use Drupal\user\Entity\User;
use Drupal\views\Entity\View;
use Drupal\views\Views;
use Drupal\views\ViewExecutable;
use Drupal\views\ViewExecutableFactory;
use Drupal\views\DisplayPluginCollection;
use Drupal\views\Plugin\views\display\DefaultDisplay;
use Drupal\views\Plugin\views\display\Page;
use Drupal\views\Plugin\views\style\DefaultStyle;
use Drupal\views\Plugin\views\style\Grid;
use Drupal\views\Plugin\views\row\Fields;
use Drupal\views\Plugin\views\query\Sql;
use Drupal\views\Plugin\views\pager\PagerPluginBase;
use Drupal\views\Plugin\views\query\QueryPluginBase;
use Drupal\views_test_data\Plugin\views\display\DisplayTest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tests the OG admin Members view.
 *
 * @group og
 */
class OgAdminMembersViewTest extends ViewsKernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'system',
    'user',
    'field',
    'node',
    'og',
  ];

  /**
   * Views used by this test.
   *
   * @var array
   */
  public static $testViews = ['og_members_overview'];

  /**
   * Properties that should be stored in the configuration.
   *
   * @var array
   */
  protected $configProperties = array(
    'disabled',
    'name',
    'description',
    'tag',
    'base_table',
    'label',
    'core',
    'display',
  );

  /**
   * Properties that should be stored in the executable.
   *
   * @var array
   */
  protected $executableProperties = array(
    'storage',
    'built',
    'executed',
    'args',
    'build_info',
    'result',
    'attachment_before',
    'attachment_after',
    'exposed_data',
    'exposed_raw_input',
    'old_view',
    'parent_views',
  );

  protected function setUpFixtures() {
    // Add membership and config schema.
    $this->installConfig(['og']);
    $this->installEntitySchema('og_membership');
    $this->installEntitySchema('user');
    $this->installEntitySchema('node');

    // Create a group entity type.
    $group_bundle = Unicode::strtolower($this->randomMachineName());
    NodeType::create([
      'type' => $group_bundle,
      'name' => $this->randomString(),
    ])->save();
    Og::groupTypeManager()->addGroup('node', $group_bundle);

    // Create group admin user.
    $group_admin = User::create(['name' => $this->randomString()]);
    $group_admin->save();

    // Create a group.
    $this->group = Node::create([
      'title' => $this->randomString(),
      'type' => $group_bundle,
      'uid' => $group_admin->id(),
    ]);
    $this->group->save();

    parent::setUpFixtures();
  }


  /**
   * Tests the initDisplay() and initHandlers() methods.
   */
  public function testInitMethods() {
    $view = Views::getView('test_entity_area');
    $preview = $view->preview('default', ['node', $this->group->id()]);

    $map = [
      'Name' => '//*[@id="view-name-table-column"]/a/text()',
    ];


    foreach ($map as $value => $xpath) {
      $result = $this->xpath($xpath);
      $this->assertEquals($value, $result);
    }
  }

}
