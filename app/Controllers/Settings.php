<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Branches_model as branchesmodel;
use App\Models\Settings_model as settingsmodel;

//use App\Models\Home_model as homemodel;

class Settings extends BaseController
{
    protected $role = 0;
    protected $branch = 0;

    /**
     * constructor
     */
    public function __construct()
    {
        $session = session();
        $this->role = $session->get('role');
        $this->branch = $session->get('branch');
    }

    public function index()
    {
        if ($this->role == 0) {
            $settingsmodel = new settingsmodel();
            $data['settings'] = $settingsmodel->getSettings();
        } else {
            $branchesmodel = new branchesmodel();
            $data['settings'] = $branchesmodel->getBranchSettings($this->branch);
        };
        return $this->view("settings", $data);
    }

    public function updatesettings()
    {
        $features = "";
        $formfeatures = $this->request->getVar('features');
        foreach ($formfeatures as $val) {
            $features .= (array_values($val)[0]) . ", ";
        }
        $data = array(
            'app_login' => $this->request->getVar('app_login')
        , 'allow_downloads' => $this->request->getVar('allow_downloads')
        , 'join_groups' => $this->request->getVar('join_groups')
        , 'post_prayer' => $this->request->getVar('post_prayer')
        , 'auto_approve_group_membership' => $this->request->getVar('auto_approve_group_membership')
        , 'post_testimony' => $this->request->getVar('post_testimony')
        , 'auto_approve_prayer' => $this->request->getVar('auto_approve_prayer')
        , 'auto_approve_testimony' => $this->request->getVar('auto_approve_testimony')
        , 'facebook' => $this->request->getVar('facebook')
        , 'twitter' => $this->request->getVar('twitter')
        , 'instagram' => $this->request->getVar('instagram')
        , 'youtube' => $this->request->getVar('youtube')
        , 'website' => $this->request->getVar('website')
        , 'mail_port' => $this->request->getVar('mail_port')
        , 'mail_protocol' => $this->request->getVar('mail_protocol')
        , 'mail_smtp_host' => $this->request->getVar('mail_smtp_host')
        , 'mail_password' => $this->request->getVar('mail_password')
        , 'mail_username' => $this->request->getVar('mail_username')

        , 'twilio_account_sid' => $this->request->getVar('twilio_account_sid')
        , 'twilio_auth_token' => $this->request->getVar('twilio_auth_token')
        , 'twilio_phonenumber' => $this->request->getVar('twilio_phonenumber')
        , 'termi_sender_id' => $this->request->getVar('termi_sender_id')
        , 'termi_apikey' => $this->request->getVar('termi_apikey')

        , 'prefered_gateway' => $this->request->getVar('prefered_gateway')
        , 'flutterwaves_api_key' => $this->request->getVar('flutterwaves_api_key')
        , 'payu_api_key' => $this->request->getVar('payu_api_key')
        , 'paystack_api_key' => $this->request->getVar('paystack_api_key')
        , 'currency_code' => $this->request->getVar('currency_code')
        , 'donations_link' => $this->request->getVar('donations_link')
        , 'features' => $features

        , 'churchname' => $this->request->getVar('churchname')
        , 'terms' => $this->request->getVar('terms')
        , 'privacy' => $this->request->getVar('privacy')
        , 'aboutus' => $this->request->getVar('aboutus')
        );

        if ($this->role == 0) {
            $data['fcm_server_key'] = $this->request->getVar('fcm_server_key');
            $settingsmodel = new settingsmodel();
            $settingsmodel->updateSettings($data);
        } else {
            $branchesmodel = new branchesmodel();
            $branchesmodel->updateSettings($data, $this->branch);
        }
        $session = session();
        $session->setFlashdata('success', "Settings updated successfully.");
        return redirect()->to(base_url() . '/settings');
    }

    public function terms()
    {
        $settingsmodel = new settingsmodel();
        $settings = $settingsmodel->getSettings();
        $data['churchname'] = $settings->churchname;
        $data['title'] = "Terms & Conditions";
        $data['content'] = $settings->terms;
        return view("content", $data);
    }

    public function privacy()
    {
        $settingsmodel = new settingsmodel();
        $settings = $settingsmodel->getSettings();
        $data['churchname'] = $settings->churchname;
        $data['title'] = "Privacy Policy";
        $data['content'] = $settings->privacy;
        return view("content", $data);
    }

    public function aboutus()
    {
        $settingsmodel = new settingsmodel();
        $settings = $settingsmodel->getSettings();
        $data['churchname'] = $settings->churchname;
        $data['title'] = "About Us";
        $data['content'] = $settings->aboutus;
        return view("content", $data);
    }
}
