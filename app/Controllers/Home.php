<?php

namespace App\Controllers;
use CodeIgniter\Controller;

use App\Models\Articles_model as articlesmodel;
use App\Models\Branches_model as branchesmodel;
use App\Models\Audio_model as audiomodel;
use App\Models\Books_model as booksmodel;
use App\Models\Hymns_model as hymnsmodel;
use App\Models\Members_model as membersmodel;
use App\Models\Prayer_model as prayermodel;
use App\Models\Testimony_model as testimonymodel;
use App\Models\Devotionals_model as devotionalsmodel;
use App\Models\Groups_model as groupsmodel;
use App\Models\Video_model as videomodel;
use App\Models\Donations_model as donationsmodel;
use App\Models\Settings_model as settingsmodel;

//use App\Models\Home_model as homemodel;

class Home extends BaseController
{
   protected $role = 0;
   protected $branch = 0;

	/**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $session = session();
        $this->role = $session->get('role');
        $this->branch = $session->get('branch');
    }

	public function index(){
    //articles
    //$articlesmodel = new articlesmodel();
    //$data['articles'] = $articlesmodel->getTotalItems();
    //books
    //$booksmodel = new booksmodel();
    //$data['books'] = $booksmodel->getTotalItems();
    //branches

    $branchesmodel = new branchesmodel();
    $data['branches'] = $branchesmodel->getTotalItems();
    if($this->role == 0){
      $settingsmodel = new settingsmodel();
      $settings = $settingsmodel->getSettings();
      $data['churchname'] = $settings->churchname;
      $data['currencycode'] = $settings->currency_code;
    }else{
      $data['churchname'] = $branchesmodel->getBranchName($this->branch);
      $data['currencycode'] = $branchesmodel->getBranchSettings($this->branch)->currency_code;
    }
    //audios
    $audiomodel = new audiomodel();
    $data['audios'] = $audiomodel->getTotalItems();
    //videos
    $videomodel = new videomodel();
    $data['videos'] = $videomodel->getTotalItems();
    //prayer
    //$prayermodel = new prayermodel();
    //$data['prayers'] = $prayermodel->getTotalItems();
    //testimonies
    //$testimonymodel = new testimonymodel();
    //$data['testimonies'] = $testimonymodel->getTotalItems();
    //devotionals
    //$devotionalsmodel = new devotionalsmodel();
    //$data['devotionals'] = $devotionalsmodel->getTotalItems();
    //hymns
    //$hymnsmodel = new hymnsmodel();
    //$data['hymns'] = $hymnsmodel->getTotalItems();
    //members
    $membersmodel = new membersmodel();
    $data['members'] = $membersmodel->getTotalItems();
    //groups
    $groupsmodel = new groupsmodel();
    $data['groups'] = $groupsmodel->getTotalItems();
    //donations
    $firstdayoftheweek = date('Y-m-d', strtotime("this week"));//Monday
    $today = date('Y-m-d');
    $thismonth = date('m');
    $thisyear = date('Y');
    $donationsmodel = new donationsmodel();
    $data['donations'] = $donationsmodel->getTotalItems();
    $data['donationsthisweek'] = $donationsmodel->getThisWeekDonationsAmount($firstdayoftheweek, $today);
    $data['donationsthismonth'] = $donationsmodel->getDonationsAmount($thismonth, $thisyear);
    $data['donationsthisyear'] = $donationsmodel->getDonationsAmount(0, $thisyear);
    $data['alldonations'] = $donationsmodel->getDonationsAmount(0,0);
    $data['recentdonations'] = $donationsmodel->getRecentDonations();

    return $this->view("home", $data);
	}
}
