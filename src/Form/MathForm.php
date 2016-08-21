<?php

namespace Drupal\d8_code_test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements the math form controller.
 *
 * This form takes user input, validates it, provides a preview and shows the
 * total of the sum of the user's input values in a standard Drupal message
 * upon submission.
 */
class MathForm extends FormBase {

  /**
   * Getter method for Form ID.
   *
   * @return string
   *   The unique ID of the form defined by this class.
   */
  public function getFormId() {
    return 'd8_code_test_math_form';
  }

  /**
   * Build the math form.
   *
   * @param array $form
   *   Default form array structure.
   * @param FormStateInterface $form_state
   *   Object containing current form state.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['description'] = [
      '#markup' => '<p>This is a simple form designed to take two number inputs, add them together and return the sum of them in a message upon submission.</p>',
    ];

    $form['first_number'] = [
      '#type' => 'number',
      '#title' => $this->t('First Number'),
      '#step' => 'any',
      '#ajax' => [
        'callback' => '::previewCallback',
        'wrapper' => 'preview-wrapper',
        'event' => 'keyup',
      ],
    ];

    $form['second_number'] = [
      '#type' => 'number',
      '#title' => $this->t('Second Number'),
      '#step' => 'any',
      '#ajax' => [
        'callback' => '::previewCallback',
        'wrapper' => 'preview-wrapper',
        'event' => 'keyup',
      ],
    ];

    $form['preview_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'preview-wrapper'],
    ];

    $form['preview_wrapper']['total'] = [
      '#type' => 'item',
      '#title' => $this->t('Preview:'),
      '#markup' => $this->t('Enter numeric values in both fields to see a preview of the total.'),
    ];

    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * Implements form validation.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!is_numeric($form_state->getValue('first_number'))) {
      $form_state->setErrorByName('first_number', $this->t('Please enter a valid number in the First Number field.'));
    }

    if (!is_numeric($form_state->getValue('second_number'))) {
      $form_state->setErrorByName('second_number', $this->t('Please enter a valid number in the Second Number field.'));
    }
  }

  /**
   * Implements form submit handler.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    drupal_set_message($this->t('Success: @first_number + @second_number = @number', [
      '@first_number' => $form_state->getValue('first_number'),
      '@second_number' => $form_state->getValue('second_number'),
      '@number' => $this->doAddition($form_state),
    ]));
  }

  /**
   * Implements callback for Ajax event when a number is entered.
   *
   * @param array $form
   *   From render array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current state of form.
   *
   * @return array
   *   preview section of the form.
   */
  public function previewCallback(array &$form, FormStateInterface $form_state) {
    $markup = $this->t('Enter numeric values in both fields to see a preview of the total.');

    if ($total = $this->doAddition($form_state)) {
      $markup = $this->t('Total: @total', ['@total' => $total]);
    }

    $form['preview_wrapper']['total'] = [
      '#type' => 'item',
      '#title' => $this->t('Preview:'),
      '#markup' => $markup,
    ];

    return $form['preview_wrapper'];
  }

  /**
   * Handler for doing addition on the provided form values.
   *
   * If incoming values prove to be numeric, casts them as float and returns the
   * sum. Otherwise, returns false.
   *
   * @param object $form_state
   *   Current state of form.
   *
   * @return mixed
   *   Returns float if valid values provided, otherwise returns false.
   */
  public function doAddition($form_state) {
    if (is_numeric($form_state->getValue('first_number')) && is_numeric($form_state->getValue('second_number'))) {
      return (float) $form_state->getValue('first_number') + (float) $form_state->getValue('second_number');
    }

    return FALSE;
  }

}
