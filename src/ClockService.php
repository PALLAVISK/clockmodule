<?php
namespace Drupal\clock;

class ClockService{
    public function test($city){
        $settings = \Drupal::config('clock.settings');
        $app=$settings->get('simple.id');
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET','http://worldclockapi.com/api/json/est/now'.$city.'&appid='.$app);
        $data = $response->getBody();
        return $data;
    }
}


