<?php
namespace Drupal\toolbar_always_vertical\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Url;
use Drupal\Component\Utility\Html;

class CustomForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Load the configuration.
    $config = $this->config('toolbar_always_vertical.settings');
    // kint($config->get());die;
    $form['mode'] = [
      '#type' => 'radios',
      '#title' => $this->t('Theme color mode:'),
      '#options' => [
        'light' => $this->t('Light'),
        'dark' => $this->t('Dark'),
      ],
      '#default_value' => $config->get('mode') ?: 'light',
      '#attributes' => [
        'class' => ['radio-buttons'],
      ],
      '#ajax' => [
        'callback' => '::ajaxCallback',
        'event' => 'change',
        'wrapper' => 'ajax-wrapper',
        'progress' => ['type' => 'throbber'],
      ],
    ];
    $form['menu_style'] = [
      '#type' => 'radios',
      '#title' => $this->t('Vertical & Horizontal menu style'),
      '#options' => [
        'menu-click' => $this->t('Menu click'),
        'icon-hover' => $this->t('Icon hover'),
        'icon-default' => $this->t('Icon default'),
      ],
      '#default_value' => $config->get('menu_style') ?: 'menu-click',
      '#attributes' => [
        'class' => ['radio-buttons'],
      ],
      '#ajax' => [
        'callback' => '::ajaxCallback',
        'event' => 'change',
        'wrapper' => 'ajax-wrapper',
        'progress' => ['type' => 'throbber'],
      ],
    ];
    $form['bg_image'] = [
      '#type' => 'radios',
      '#title' => $this->t('Menu with background image'),
      '#options' => [
        'option1' => '<img class="img-1 bg-1" src="/modules/custom/toolbar_always_vertical/images/img-1.jpg" alt="' . $this->t('Option 1') . '">',
        'option2' => '<img class="img-1 bg-2" src="/modules/custom/toolbar_always_vertical/images/img-2.jpg" alt="' . $this->t('Option 2') . '">',
        'option3' => '<img class="img-1" src="/modules/custom/toolbar_always_vertical/images/img-3.jpg" alt="' . $this->t('Option 3') . '">',
        'option4' => '<img class="img-1" src="/modules/custom/toolbar_always_vertical/images/img-4.jpg" alt="' . $this->t('Option 4') . '">',
        'option5' => '<img class="img-1" src="/modules/custom/toolbar_always_vertical/images/img-5.jpg" alt="' . $this->t('Option 5') . '">',

      ],
      '#default_value' => $config->get('bg_image') ?: '',
      '#attributes' => [
        'class' => ['bg-image'],
      ],
      '#ajax' => [
        'callback' => '::ajaxCallback',
        'event' => 'change',
        'wrapper' => 'ajax-wrapper',
        'progress' => ['type' => 'throbber'],
      ],
      '#attached' => [
        'drupalSettings' => [
          'toolbar_always_vertical' => [
            'ss_theme_setting' => $config->get(),
          ],
        ],
      ],
    ];
    // $form['#attached']['drupalSettings']['toolbar_always_vertical']['ss_theme_setting'] = $config->get();
    $form['#prefix'] = '<div id="ajax-wrapper">';
    $form['#suffix'] = '</div>';

    $form['actions']['clear_all'] = [
      '#type' => 'button',
      '#value' => $this->t('Clear all'),
      '#attributes' => [
        'class' => ['tf-button', 'cursor-pointer', 'w-full', 'button-clear-select'],
      ],
      '#executes_submit_callback' => FALSE,
      '#limit_validation_errors' => [],
      '#ajax' => [
        'callback' => '::ajaxCallback',
        'event' => 'change',
        'wrapper' => 'ajax-wrapper',
        'progress' => ['type' => 'throbber'],
      ],
    ];

    return $form;
  }
  /**
   * AJAX callback function.
   */
  public function ajaxCallback(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand('#ajax-wrapper', $form));
    $this->configFactory->getEditable('toolbar_always_vertical.settings')
      ->set('mode', $form_state->getValue('mode'))
      ->set('width_style', $form_state->getValue('width_style'))
      ->set('menu_style', $form_state->getValue('menu_style'))
      ->set('menu_position', $form_state->getValue('menu_position'))
      ->set('bg_image', $form_state->getValue('bg_image'))
      ->save();
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    }

}
