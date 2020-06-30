<?php

namespace Drupal\custom_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Contribute form.
 */
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

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#size' => 60,
      '#maxlength' => 128,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('E-mail'),
    ];

    $form['gender'] = [
      '#type' => 'select',
      '#title' => $this->t('Gender'),
      '#options' => [
        'male' => $this->t('Male'),
        'female' => $this->t('Female'),
      ],
      '#empty_option' => $this->t('-select-'),
      '#required' => 'TRUE',
    ];

    $form['phone'] = [
      '#type' => 'tel',
      '#title' => $this->t('Contact Number'),
      '#maxlength' => 10,
    ];

    $form['address'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Address'),
      '#required' => 'TRUE',
    ];

    $form['privacy_policy'] = [
      '#type' => 'checkbox',
      '#default_value' => "",
      '#title' => $this->t('I agree to the above terms and conditions'),
    ];

    $form['settings']['exercise'] = [
      '#type' => 'radios',
      '#title' => $this->t('Do you any exercise'),
      '#options' => [0 => $this->t('Closed'), 1 => $this->t('Active')],
      '#required' => 'TRUE',
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Username field validation.
    $name = $form_state->getValue('name');
    if (preg_match('/[^\x{80}-\x{F7} a-zA-Z0-9@_.\'-]/i', $name)) {
      $form_state->setErrorByName('name', $this->t('*This username will accept only a-Z,0-9, - _ @ .'));
    }
    if (empty($name)) {
      $form_state->setErrorByName('name', $this->t('*You must enter a username.'));
    }

    // Email_address field validation.
    $email = $form_state->getValue('email');
    if (empty($email)) {
      $form_state->setErrorByName('email', $this->t('*You must enter an email address.'));
    }
    if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])↪*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",
               $email)) {
      $form_state->setErrorByName('email', $this->t('*The email address "$email" is not valid.'));
    }

    // Privacy_policy field validation.
    $privacy_policy = $form_state->getValue('privacy_policy');
    if (empty($privacy_policy)) {
      $form_state->setErrorByName('privacy_policy', t('*You should accept our privacy policy.'));
    }
    // Mobile field validation.
    $phone = $form_state->getValue('phone');
    if (empty($phone)) {
      $form_state->setErrorByName('phone', $this->t('*You must enter phone number.'));
    }
    if (preg_match("/[^0-9\+]/", $phone)) {
      $form_state->setErrorByName('phone', $this->t("Phone number contains only numbers"));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $conn = Database::getConnection();
    $conn->insert('register')->fields(
      [
        'name' => $form_state->getValue('name'),
        'email' => $form_state->getValue('email'),
        'gender' => $form_state->getValue('gender'),
        'phone' => $form_state->getValue('phone'),
        'address' => $form_state->getValue('address'),
        'privacy_policy' => $form_state->getValue('privacy_policy'),
        'exercise' => $form_state->getValue('exercise'),
      ]
    )->execute();
    $url = Url::fromRoute('custom_module.thankyou');
    $form_state->setRedirectUrl($url);
  }

}
