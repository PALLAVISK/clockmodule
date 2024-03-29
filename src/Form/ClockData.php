<?php
namespace Drupal\clock\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


class ClockData extends ConfigFormBase {

  public function getFormId() {
 
    return 'simple_config_form';
 
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
 
    $config = $this->config('clock.settings');
 
    $form['id'] = array(
 
      '#type' => 'textfield',
 
      '#title' => $this->t('apid'),
 
      '#default_value' => $config->get('simple.id'),
 
      '#required' => TRUE,
 
    );
    return $form;
}
public function submitForm(array &$form, FormStateInterface $form_state) {
 
    $config = $this->config('clock.settings');
 
    $config->set('simple.id', $form_state->getValue('id'));
 
    $config->save();
 
    return parent::submitForm($form, $form_state);
 
  }
  protected function getEditableConfigNames() {
 
    return [
 
      'clock.settings',
 
    ];
 
  }
 
}