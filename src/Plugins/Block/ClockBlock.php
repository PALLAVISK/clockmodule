<?php
namespace Drupal\clock\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface; 
use Drupal\Component\Serialization\Json;
use Drupal\clock\ClockService;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
* Provides a clock_custom_block with a simple text.
*
* @Block(
*   id = "clock_custom_block",
*   admin_label = @Translation("clock block"),
* )
*/

//implements ContainerFactoryPluginInterface
class ClockBlock extends BlockBase implements ContainerFactoryPluginInterface{

  protected $clock_service;

   /**
   * {@inheritdoc}
   */
    public function __construct(array $configuration, $plugin_id, $plugin_definition, ClockService $clock_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->clock_servcie = $clock_service;
  }
  
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
      return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('clock.client')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();
    $form['city'] = array(
      '#type' => 'textfield',
      '#title' => t('city name:'),
          );
    $form['description'] = array(
      '#type' => 'textarea',
      '#title' => t('city desc:'),
         );
    
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {

    $this->setConfigurationValue('city', $form_state->getValue('city'));
    $this->setConfigurationValue('description', $form_state->getValue('description'));

   
  }
  
  /**
   * {@inheritdoc}
   */
  public function build() {
   

    $config = $this->getConfiguration();
    $confi=\Drupal::config('clock.settings');
    
    $appid=$confi->get('app');
    $appid=$confi->get('simple.id');
    $serv = \Drupal::service('clock.client');
    $serviCall=$serv->test($city);
    

    $jsonObj=Json::decode($serviCall);
    $city_name = isset($config['city']) ? $config['city'] : '';
    $description = isset($config['description']) ? $config['description'] : '';
    
    
    return array(
      '#theme' => 'ClockBlock',
      '#type' => 'markup',
      '#titles' => $city_name,
      '#datetime' => $jsonObj['main']['currentDateTime'] ,
      '#day' => $jsonObj['main']['dayOfTheWeek'] ,
      '#timezone' => $jsonObj['main']['timeZoneName'] ,
      '#ordinal_date' => $jsonObj['main']['ordinalDate'] ,
      '#description' => $description,
    );
  }
   
}