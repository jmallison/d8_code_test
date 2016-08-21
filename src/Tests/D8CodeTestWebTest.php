<?php

namespace Drupal\d8_code_test\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Ensure that the d8_code_test math form works properly.
 *
 * @see Drupal\simpletest\WebTestBase
 *
 * SimpleTest uses group annotations to help you organize your tests.
 *
 * @group d8_code_test
 *
 * @ingroup d8_code_test
 */
class D8CodeTestWebTest extends WebTestBase {

  /**
   * Our module dependencies.
   *
   * @var array List of test dependencies.
   */
  static public $modules = ['d8_code_test'];

  /**
   * The installation profile to use with this test.
   *
   * @var string Installation profile required for test.
   */
  protected $profile = 'minimal';

  /**
   * Test the math form.
   */
  public function testMathForm() {

    // Verify that anonymous can access the page.
    $this->drupalGet('d8_code_test');
    $this->assertResponse(200, 'The D8 Code Test page is available.');

    // Post the form.
    $edit = [
      'first_number' => '6',
      'second_number' => '21.5',
    ];

    $this->drupalPostForm('/d8_code_test', $edit, t('Submit'));
    $this->assertText('Success: 6 + 21.5 = 27.5');
  }

}
