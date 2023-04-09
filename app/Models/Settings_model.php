<?php

namespace App\Models;

use CodeIgniter\Model;

class Settings_model extends Model
{
    protected $db;
    public $status = 'error';
    public $message = 'Error processing requested operation.';
    public $role = 0;

    public function __construct()
    {
        parent::__construct();
        $session = session();
        $this->role = $session->get('role');
    }

    function getFcmServerKey()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('settings');
        $builder->select('settings.fcm_server_key');
        $builder->where('id', 100);
        $query = $builder->get();
        return $query->getRow(0)->fcm_server_key;
    }

    function getSettings()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('settings');
        $builder->select('settings.*');
        $builder->where('id', 100);
        $query = $builder->get();
        $row = $query->getRow(0);
        if ($row && $row->features == "") {
            $row->features = "bible,audiomessages, videomessages, donations, livestreams, events, articles, hymns, radio, photos, groups, prayer, testimony, devotionals, notes, books, gosocial";
        }
        return $row;
    }

    function getAppSettings()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('settings');
        $builder->select('settings.features,
      settings.app_login,
      settings.allow_downloads,
      settings.join_groups,
      settings.post_prayer,
      settings.post_testimony,
      settings.facebook,
      settings.twitter,
      settings.instagram,
      settings.youtube,
      settings.website,
      settings.donations_link,
      settings.post_prayer,
      settings.post_prayer,
      settings.post_prayer,
      ');
        $builder->where('id', 100);
        $query = $builder->get();
        $row = $query->getRow(0);
        if ($row == NULL || $row->features == "") {
            $row->features = "bible,audiomessages, videomessages, donations, livestreams, events, articles, hymns, radio, photos, groups, prayer, testimony, devotionals, notes, books, gosocial";
        }
        return $row;
    }


    function updateSettings($info)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('settings');
        $builder->where('id', 100);
        $builder->update($info);
    }

    public function getEmailConfig($set1, $set2)
    {
        $config = new \stdClass;
        $config->mail_username = $set2->mail_username;
        $config->mail_password = $set2->mail_password;
        $config->mail_smtp_host = $set2->mail_smtp_host;
        $config->mail_protocol = $set2->mail_protocol;
        $config->mail_port = $set2->mail_port;
        /*if($this->role == 0){
          $config->mail_username = $set2->mail_username;
          $config->mail_password = $set2->mail_password;
          $config->mail_smtp_host = $set2->mail_smtp_host;
          $config->mail_protocol = $set2->mail_protocol;
          $config->mail_port = $set2->mail_port;
        }else{
          if($set1->mail_username != ""
          && $set1->mail_password != ""
          && $set1->mail_smtp_host != ""
          && $set1->mail_protocol != ""
          && $set1->mail_port != 0){
            $config->mail_username = $set1->mail_username;
            $config->mail_password = $set1->mail_password;
            $config->mail_smtp_host = $set1->mail_smtp_host;
            $config->mail_protocol = $set1->mail_protocol;
            $config->mail_port = $set1->mail_port;
          }else{
            $config->mail_username = $set2->mail_username;
            $config->mail_password = $set2->mail_password;
            $config->mail_smtp_host = $set2->mail_smtp_host;
            $config->mail_protocol = $set2->mail_protocol;
            $config->mail_port = $set2->mail_port;
          }
        }*/
        return $config;
    }

    public function getSMSConfig($set1, $set2, $smsgateway)
    {
        $config = new \stdClass;
        if ($this->role == 0) {
            if ($smsgateway == "twilio") {
                $config->twilio_account_sid = $set2->twilio_account_sid;
                $config->twilio_auth_token = $set2->twilio_auth_token;
                $config->twilio_phonenumber = $set2->twilio_phonenumber;
            } else {
                $config->termi_apikey = $set2->termi_apikey;
                $config->termi_sender_id = $set2->termi_sender_id;
            }
        } else {
            if ($smsgateway == "twilio" && $settings->twilio_account_sid != ""
                && $settings->twilio_auth_token != ""
                && $settings->twilio_auth_token != "") {
                $config->twilio_account_sid = $set1->twilio_account_sid;
                $config->twilio_auth_token = $set1->twilio_auth_token;
                $config->twilio_phonenumber = $set1->twilio_phonenumber;
            } else if ($smsgateway == "twilio") {
                $config->twilio_account_sid = $set2->twilio_account_sid;
                $config->twilio_auth_token = $set2->twilio_auth_token;
                $config->twilio_phonenumber = $set2->twilio_phonenumber;
            } else if ($smsgateway == "termii" && $settings->termi_apikey != ""
                && $settings->termi_sender_id != "") {
                $config->termi_apikey = $set1->termi_apikey;
                $config->termi_sender_id = $set1->termi_sender_id;
            } else {
                $config->termi_apikey = $set2->termi_apikey;
                $config->termi_sender_id = $set2->termi_sender_id;
            }
        }
        return $config;
    }
}

?>
