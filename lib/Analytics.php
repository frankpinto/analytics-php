<?php

if (!function_exists('json_encode')) {
    throw new Exception('Analytics needs the JSON PHP extension.');
}

require(dirname(__FILE__) . '/Analytics/Client.php');


class Analytics {

  public $blocked_uas = array(
      'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
      'DoCoMo/2.0 N905i(c100;TB;W24H16) (compatible; Googlebot-Mobile/2.1; +http://www.google.com/bot.html)',
      'Mozilla/5.0 (compatible; MJ12bot/v1.4.3; http://www.majestic12.co.uk/bot.php?+)',
      'SAMSUNG-SGH-E250/1.0 Profile/MIDP-2.0 Configuration/CLDC-1.1 UP.Browser/6.2.3.3.c.1.101 (GUI) MMP/2.0 (compatible; Googlebot-Mobile/2.1; +http://www.google.com/bot.html)'
    );
  
  private static $client;

  /**
   * Initializes the default client to use. Uses the socket consumer by default.
   * @param  string $secret   your project's secret key
   * @param  array  $options  passed straight to the client
   */
  public static function init($secret, $options = array()) {

  	if (!$secret){
  		throw new Exception("Analytics::init Secret parameter is required");
  	}

    self::$client = new Analytics_Client($secret, $options);
  }

  /**
   * Tracks a user action
   * @param  string $user_id    user id string
   * @param  string $event      name of the event
   * @param  array  $properties properties associated with the event [optional]
   * @param  number $timestamp  unix seconds since epoch (time()) [optional]
   * @param  array  $context    [optional]
   * @return boolean whether the track call succeeded
   */
  public static function track($user_id, $event, $properties = null,
                                $timestamp = null, $context = array()) {
    self::check_client();
    return self::$client->track($user_id, $event, $properties, $timestamp,
                                $context);
  }

  /**
   * Tags traits about the user.
   * @param  string  $user_id
   * @param  array   $traits
   * @param  number  $timestamp  unix seconds since epoch (time()) [optional]
   * @param  array   $context    [optional]
   * @return boolean whether the track call succeeded
   */
  public static function identify($user_id, $traits = array(),
                                    $timestamp = null, $context = array()) {
    self::check_client();
    return self::$client->identify($user_id, $traits, $timestamp, $context);
  }

  /**
   * Aliases the user id from a temporary id to a permanent one
   * @param  string $from      user id to alias from
   * @param  string $to        user id to alias to
   * @param  number $timestamp unix seconds since epoch (time()) [optional]
   * @param  array  $context   [optional]
   * @return boolean whether the alias call succeeded
   */
  public static function alias($from, $to, $timestamp = null,
                                $context = array()) {
    self::check_client();
    return self::$client->alias($from, $to, $timestamp, $context);
  }

  /**
   * Ensures that the client is indeed set. Throws an exception when not set.
   */
  private static function check_client() {

    if (self::$client == null) {
      throw new Exception("Analytics::init must be called " .
                          "before track or identify");
    }
  }
}
