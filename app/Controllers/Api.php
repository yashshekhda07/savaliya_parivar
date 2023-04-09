<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Articles_model as articlesmodel;
use App\Models\Branches_model as branchesmodel;
use App\Models\Settings_model as settingsmodel;
use App\Models\Account_model as accountmodel;
use App\Models\Verify_model as verifymodel;
use App\Models\Media_model as mediamodel;
use App\Models\Photos_model as photosmodel;
use App\Models\Radio_model as radiomodel;
use App\Models\Livestream_model as livestreammodel;
use App\Models\Books_model as booksmodel;
use App\Models\Groups_model as groupsmodel;
use App\Models\Prayer_model as prayermodel;
use App\Models\Testimony_model as testimonymodel;
use App\Models\Fcm_model as fcmmodel;
use App\Models\Events_model as eventsmodel;
use App\Models\Devotionals_model as devotionalsmodel;
use App\Models\Hymns_model as hymnsmodel;
use App\Models\Inbox_model as inboxmodel;

class Api extends BaseController
{
    //store user fcm token
    function storeFcmToken()
    {
        $data = $this->get_data();
        $fcmmodel = new fcmmodel();
        if (isset($data->token) && $data->token != "") {
            $token = $data->token;
            $version = "v2";
            $data = array("token" => $token, "app_version" => $version);
            $fcmmodel->storeUserFcmToken($data);
        }
        echo json_encode(array("status" => $this->fcm_model->status, "msg" => $this->fcm_model->message));
        exit;
    }

    //get settings
    public function initapp()
    {
        $data = $this->get_data();
        $inboxmodel = new inboxmodel();
        $lastid = isset($data->lastid) ? filter_var($data->lastid, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : 0;
        $notification_count = $inboxmodel->get_last_seen_notification_count($lastid);
        $settingsmodel = new settingsmodel();
        $settings = $settingsmodel->getAppSettings();
        //recommended videos/audios
        $eventsmodel = new eventsmodel();
        $upcoming_events = $eventsmodel->getUpcomingEvents();
        //recommended videos/audios
        $mediamodel = new mediamodel();
        $latest_media = $mediamodel->getLatestMedia();
        //latest articles
        $articlesmodel = new articlesmodel();
        $latest_articles = $articlesmodel->getLatestArticles();
        //latest books
        $booksmodel = new booksmodel();
        $latest_books = $booksmodel->getLatestBooks();
        echo json_encode(array("status" => "ok"
        , "notification_count" => $notification_count
        , "latest_media" => $latest_media
        , "latest_articles" => $latest_articles
        , "latest_books" => $latest_books
        , "upcoming_events" => $upcoming_events
        , "settings" => $settings
        , "statusCode" => 0));
        exit;
    }

    function getitemdata()
    {
        $data = $this->get_data();
        //var_dump($data); die;
        $id = isset($data->id) ? filter_var($data->id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : 0;
        $type = isset($data->type) ? filter_var($data->type, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : "";
        if ($type == "") {
            echo json_encode(array("status" => "error"));
            exit;
        }

        if ($type == "Devotional") {
            $devotionalsmodel = new devotionalsmodel();
            $devotional = $devotionalsmodel->getDevotionalInfo($id);
            echo json_encode(array("status" => "ok", "devotional" => $devotional));
        }
        if ($type == "Event") {
            $eventsmodel = new eventsmodel();
            $events = $eventsmodel->getEventInfo($id);
            echo json_encode(array("status" => "ok", "events" => $events));
        }
        if ($type == "Article") {
            $articlesmodel = new articlesmodel();
            $article = $articlesmodel->getArticleInfo($id);
            echo json_encode(array("status" => "ok", "articles" => $article));
        }
        exit;
    }

    function getBibleVersions()
    {
        $booksmodel = new booksmodel();
        $versions = $booksmodel->biblesListing();
        echo json_encode(array("status" => "ok", "versions" => $versions));
        exit;
    }

    //fetch events
    function fetch_events()
    {
        $data = $this->get_data();
        $month = isset($data->month) ? filter_var($data->month, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : date("m");
        $year = isset($data->year) ? filter_var($data->year, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : date("Y");
        $eventsmodel = new eventsmodel();
        $results = $eventsmodel->fetchMonthsEvents($month, $year);
        echo json_encode(array("status" => "ok", "events" => $results));
        exit;
    }

    //fetch events
    function fetch_devotionals()
    {
        $data = $this->get_data();
        $month = isset($data->month) ? filter_var($data->month, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : date("m");
        $year = isset($data->year) ? filter_var($data->year, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : date("Y");
        $devotionalsmodel = new devotionalsmodel();
        $results = $devotionalsmodel->fetchMonthsDevotionals($month, $year);
        echo json_encode(array("status" => "ok", "devotionals" => $results));
        exit;
    }

    //fetch hymns
    function fetch_hymns()
    {
        $data = $this->get_data();
        $results = [];
        $isLastPage = false;
        $query = isset($data->query) ? filter_var($data->query, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : "";
        $page = 0;
        if (isset($data->page)) {
            $page = $data->page;
        }

        $hymnsmodel = new hymnsmodel();
        $results = $hymnsmodel->hymnsListing($page, $query);
        $total_items = $hymnsmodel->get_total_hymns($query);
        $isLastPage = (($page + 1) * 20) >= $total_items;

        echo json_encode(array("status" => "ok", "hymns" => $results, "isLastPage" => $isLastPage));
        exit;
    }

    //fetch inbox
    function fetch_inbox()
    {
        $data = $this->get_data();
        $results = [];
        $isLastPage = false;
        $page = 0;
        if (isset($data->page)) {
            $page = $data->page;
        }
        $inboxmodel = new inboxmodel();
        $results = $inboxmodel->fetchInbox($page);
        $total_items = $inboxmodel->get_total_inbox();
        $isLastPage = (($page + 1) * 20) >= $total_items;
        echo json_encode(array("status" => "ok", "isLastPage" => $isLastPage, "inbox" => $results));
        exit;
    }

    //search audios/videos
    function search()
    {
        $data = $this->get_data();
        $result = [];
        if (isset($data->query)) {
            $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : "null";
            $query = $data->query;
            $offset = 0;
            if (isset($data->offset)) {
                $offset = $data->offset;
            }
            $mediamodel = new mediamodel();
            $result = $mediamodel->searchListing($query, $offset, $email);
        }
        echo json_encode(array("status" => "ok", "search" => $result));
        exit;
    }

    //fetch media
    function fetchmedia()
    {
        $data = $this->get_data();
        $results = [];
        $isLastPage = false;
        if (isset($data->media_type)) {
            $type = $data->media_type;
            $page = 0;
            if (isset($data->page)) {
                $page = $data->page;
            }
            $mediamodel = new mediamodel();
            $results = $mediamodel->fetch_media($type, $page);
            $total_items = $mediamodel->get_total_media($type);
            $isLastPage = (($page + 1) * 20) >= $total_items;
        }
        echo json_encode(array("status" => "ok", "media" => $results, "isLastPage" => $isLastPage));
        exit;
    }

    //fetch media views
    function update_media_total_views()
    {
        $data = $this->get_data();
        $results = [];
        $isLastPage = false;
        if (isset($data->media)) {
            $media = $data->media;
            $mediamodel = new mediamodel();
            $mediamodel->update_media_total_views($media);
        }
        echo json_encode(array("status" => "ok"));
        exit;
    }

    //fetch livestreams
    function fetchlivestreams()
    {
        $data = $this->get_data();
        $results = [];
        $isLastPage = false;
        $page = 0;
        if (isset($data->page)) {
            $page = $data->page;
        }
        $livestreammodel = new livestreammodel();
        $results = $livestreammodel->fetch_livestreams_app($page);
        $total_items = $livestreammodel->get_total_livestreams();
        $isLastPage = (($page + 1) * 20) >= $total_items;
        echo json_encode(array("status" => "ok", "livestreams" => $results, "isLastPage" => $isLastPage));
        exit;
    }

    //fetch radio
    function fetchradios()
    {
        $data = $this->get_data();
        $results = [];
        $isLastPage = false;
        $page = 0;
        if (isset($data->page)) {
            $page = $data->page;
        }
        $radiomodel = new radiomodel();
        $results = $radiomodel->fetch_radio($page);
        $total_items = $radiomodel->get_total_radio();
        $isLastPage = (($page + 1) * 20) >= $total_items;
        echo json_encode(array("status" => "ok", "radios" => $results, "isLastPage" => $isLastPage));
        exit;
    }

    //fetch books
    function fetchbooks()
    {
        $data = $this->get_data();
        $results = [];
        $isLastPage = false;
        $page = 0;
        if (isset($data->page)) {
            $page = $data->page;
        }
        $booksmodel = new booksmodel();
        $results = $booksmodel->fetch_books($page);
        $total_items = $booksmodel->get_total_books();
        $isLastPage = (($page + 1) * 20) >= $total_items;
        echo json_encode(array("status" => "ok", "books" => $results, "isLastPage" => $isLastPage));
        exit;
    }

    //fetch articles
    function fetcharticles()
    {
        $data = $this->get_data();
        $results = [];
        $isLastPage = false;
        $page = 0;
        if (isset($data->page)) {
            $page = $data->page;
        }
        $articlesmodel = new articlesmodel();
        $results = $articlesmodel->fetch_articles($page);
        $total_items = $articlesmodel->get_total_articles_app();
        $isLastPage = (($page + 1) * 20) >= $total_items;
        echo json_encode(array("status" => "ok", "articles" => $results, "isLastPage" => $isLastPage));
        exit;
    }

    //fetch photos
    function fetchphotos()
    {
        $data = $this->get_data();
        $results = [];
        $isLastPage = false;
        $page = 0;
        if (isset($data->page)) {
            $page = $data->page;
        }
        $photosmodel = new photosmodel();
        $results = $photosmodel->fetch_photos($page);
        $total_items = $photosmodel->get_total_photos();
        $isLastPage = (($page + 1) * 20) >= $total_items;
        echo json_encode(array("status" => "ok", "photos" => $results, "isLastPage" => $isLastPage));
        exit;
    }

    //fetch prayers
    function fetchprayers()
    {
        $data = $this->get_data();
        $results = [];
        $isLastPage = false;
        $page = 0;
        if (isset($data->page)) {
            $page = $data->page;
        }
        $prayermodel = new prayermodel();
        $results = $prayermodel->fetch_items($page);
        $total_items = $prayermodel->get_total_items();
        $isLastPage = (($page + 1) * 20) >= $total_items;
        echo json_encode(array("status" => "ok", "prayers" => $results, "isLastPage" => $isLastPage));
        exit;
    }

    //fetch groups
    function fetchgroups()
    {
        $data = $this->get_data();
        $results = [];
        $isLastPage = false;
        $email = "null";
        if (isset($data->email)) {
            $email = $data->email;
        }
        $page = 0;
        if (isset($data->page)) {
            $page = $data->page;
        }
        $groupsmodel = new groupsmodel();
        $results = $groupsmodel->fetch_items($email, $page);
        $total_items = $groupsmodel->get_total_items();
        $isLastPage = (($page + 1) * 20) >= $total_items;
        echo json_encode(array("status" => "ok", "groups" => $results, "isLastPage" => $isLastPage));
        exit;
    }

    //fetch my groups
    function fetchmygroups()
    {
        $data = $this->get_data();
        $results = [];
        $isLastPage = false;
        $email = "null";
        if (isset($data->email)) {
            $email = $data->email;
        }
        $page = 0;
        if (isset($data->page)) {
            $page = $data->page;
        }
        $groupsmodel = new groupsmodel();
        $results = $groupsmodel->fetchmygroups($email, $page);
        $total_items = $groupsmodel->get_my_total_groups($email);
        $isLastPage = (($page + 1) * 20) >= $total_items;
        echo json_encode(array("status" => "ok", "groups" => $results, "isLastPage" => $isLastPage));
        exit;
    }

    //fetch group activities
    function fetchgroupevents()
    {
        $data = $this->get_data();
        $groupid = 0;
        if (isset($data->groupid)) {
            $groupid = $data->groupid;
        }
        $month = isset($data->month) ? filter_var($data->month, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : date("m");
        $year = isset($data->year) ? filter_var($data->year, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : date("Y");
        $groupsmodel = new groupsmodel();
        $results = $groupsmodel->fetchMonthsEvents($groupid, $month, $year);
        echo json_encode(array("status" => "ok", "events" => $results));
        exit;
    }

    //join groups
    public function joingroup()
    {
        $email = $this->request->getVar('email');
        $groupid = $this->request->getVar('groupid');
        $settingsmodel = new settingsmodel();
        $status = $settingsmodel->getSettings()->auto_approve_group_membership;
        $info = array(
            'groupid' => $groupid,
            'email' => $email,
            'status' => $status,
        );
        $groupsmodel = new groupsmodel();
        $groupsmodel->addNewGroupMember($info);
        echo json_encode(array("status" => "ok", "approved" => $status));
        exit;
    }

    //fetch testimonies
    function fetchtestimonies()
    {
        $data = $this->get_data();
        $results = [];
        $isLastPage = false;
        $page = 0;
        if (isset($data->page)) {
            $page = $data->page;
        }
        $testimonymodel = new testimonymodel();
        $results = $testimonymodel->fetch_items($page);
        $total_items = $testimonymodel->get_total_items();
        $isLastPage = (($page + 1) * 20) >= $total_items;
        echo json_encode(array("status" => "ok", "testimonies" => $results, "isLastPage" => $isLastPage));
        exit;
    }

    //fetch testimonies
    function fetchbranches()
    {
        $data = $this->get_data();
        $results = [];
        $isLastPage = false;
        $page = 0;
        if (isset($data->page)) {
            $page = $data->page;
        }
        $branchesmodel = new branchesmodel();
        $results = $branchesmodel->fetch_items($page);
        $total_items = $branchesmodel->get_total_items();
        $isLastPage = (($page + 1) * 20) >= $total_items;
        echo json_encode(array("status" => "ok", "branches" => $results, "isLastPage" => $isLastPage));
        exit;
    }

    public function submitprayer()
    {
        $title = $this->request->getVar('title');
        $requester = $this->request->getVar('requester');
        $content = $this->request->getVar('content');
        $settingsmodel = new settingsmodel();
        $status = $settingsmodel->getSettings()->auto_approve_prayer;
        $info = array(
            'title' => $title,
            'branch' => 1,
            'content' => $content,
            'requester' => $requester,
            'status' => $status,
        );
        $prayermodel = new prayermodel();
        $prayermodel->addNewItem($info);
        echo json_encode(array("status" => "ok", "approved" => $status));
        exit;
    }

    public function submittestimony()
    {
        $title = $this->request->getVar('title');
        $testifier = $this->request->getVar('testifier');
        $content = $this->request->getVar('content');
        $settingsmodel = new settingsmodel();
        $status = $settingsmodel->getSettings()->auto_approve_testimony;
        $info = array(
            'title' => $title,
            'branch' => 1,
            'content' => $content,
            'testifier' => $testifier,
            'status' => $status,
        );
        $testimonymodel = new testimonymodel();
        $testimonymodel->addNewItem($info);
        echo json_encode(array("status" => "ok", "approved" => $status));
        exit;
    }

    //authentication functions
    public function loginapp()
    {
        $data = $this->get_data();
        if (!empty($data)) {
            $phonenumber = isset($data->phonenumber) ? filter_var($data->phonenumber, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : "";
            $password = isset($data->password) ? filter_var($data->password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : "";

            $_phone_error = $phonenumber != "" ? "" : "Phone number Address Is not valid!";
            $_password_error = $password == "" ? "Password is empty!" : "";
            if ($_phone_error != "" || $_password_error != "") {
                echo json_encode(array("status" => "error", "message" => $_phone_error . "\n" . $_password_error, "statuscode" => 0));
                exit;
            } else {
                $accountmodel = new accountmodel();
                $user = $accountmodel->authenticateUser($phonenumber, $password);
                if ($user && $user->verified == 1) {
                    echo json_encode(array("status" => $accountmodel->status, "message" => $accountmodel->message, "user" => $user, "statuscode" => 1));
                    exit;
                }
                //var_dump($accountmodel->status); die;
                echo json_encode(array("status" => $accountmodel->status, "message" => $accountmodel->message, "user" => $user, "statuscode" => 0));
                exit;
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "No data found", "statuscode" => 0));
            exit;
        }
    }

    //delete my account
    public function deletemyaccount()
    {
        $data = $this->get_data();
        if (!empty($data)) {
            $phonenumber = isset($data->phonenumber) ? filter_var($data->phonenumber, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : "";

            if ($phonenumber == "") {
                echo json_encode(array("status" => "error", "message" => "Phone number Is not valid!"));
                exit;
            } else {
                $accountmodel = new accountmodel();
                $accountmodel->deletemyaccount($phonenumber);
                //var_dump($accountmodel->status); die;
                echo json_encode(array("status" => $accountmodel->status, "message" => $accountmodel->message, "statuscode" => 0));
                exit;
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "No data found", "statuscode" => 0));
            exit;
        }
    }

    /**
     * This function used to register user
     */
//    public function createaccount()
//    {
//        $data = $this->get_data();
//        if (!empty($data)) {
//            $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : "";
//            $password = isset($data->password) ? filter_var($data->password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : "";
//
//            $_email_error = $email != "" ? "" : "Email Address Is not valid!";
//            $_password_error = $password == "" ? "Password is empty!" : "";
//            if ($_email_error != "" || $_password_error != "") {
//                echo json_encode(array("status" => "error", "message" => $_email_error . "\n" . $_password_error));
//                exit;
//            } else {
//                $accountmodel = new accountmodel();
//                $accountmodel->createAccount($email, $password);
//                if ($accountmodel->status == "ok") {
//                    $settingsmodel = new settingsmodel();
//                    $adminsettings = $settingsmodel->getSettings();
//                    //send email
//                    $emailconfig = $settingsmodel->getEmailConfig(NULL, $adminsettings);
//                    $branchname = $adminsettings->churchname;
//                    $link = $this->getVerificationLink($email);
//                    $subject = "Email Verification";
//                    $htmlContent = '<p>Thank you for registering on our platform.</p>';
//                    $htmlContent .= '<p>Please click on the link below to verify your email</p>';
//                    $this->sendEmail($branchname, $emailconfig, $email, $subject, $this->getActivationEmailTemplate($link, "Verify Email", $htmlContent));
//                }
//                //var_dump($accountmodel->status); die;
//                echo json_encode(array("status" => $accountmodel->status, "message" => $accountmodel->message));
//                exit;
//            }
//        } else {
//            echo json_encode(array("status" => "error", "message" => "No data found"));
//            exit;
//        }
//    }

        public function createaccount()
    {
        $data = $this->get_data();
        if (!empty($data)) {
            $phonenumber = isset($data->phonenumber) ? filter_var($data->phonenumber, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : "";
            $password = isset($data->password) ? filter_var($data->password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : "";
            $confirm_password = isset($data->confirm_password) ? filter_var($data->confirm_password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : "";
            $_phone_error = $phonenumber != "" ? "" : "Phone number Address Is not valid!";
            $_password_error = $password == "" ? "Password is empty!" : "";
            $_confirm_password_error = $confirm_password == "" ? "Confirm password Password is empty!" : "";

            if ($_phone_error != "" || $_password_error != "") {
                echo json_encode(array("status" => "error", "message" => $_phone_error . "\n" . $_password_error . "\n" . $_confirm_password_error));
                exit;
            } else {
                if($password === $confirm_password) {
                    $accountmodel = new accountmodel();
                    $accountmodel->createAccount($phonenumber, $password);
                    echo json_encode(array("status" => $accountmodel->status, "message" => $accountmodel->message));
                    exit;
                }
                echo json_encode(array("status" => "error", "message" => "Password & confirm password not matched."));
                exit;
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "No data found."));
            exit;
        }
    }

    /**
     * resend verification email to users email Address
     */
    public function resendVerificationMail()
    {
        $data = $this->get_data();
        if (!empty($data)) {
            $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : "";
            if ($email == "") {
                echo json_encode(array("status" => "error", "message" => "Email Address Is not valid!"));
                exit;
            } else {
                $accountmodel = new accountmodel();
                if ($accountmodel->verifyEmailExists($email) == TRUE) {
                    $settingsmodel = new settingsmodel();
                    $adminsettings = $settingsmodel->getSettings();
                    //send email
                    $emailconfig = $settingsmodel->getEmailConfig(NULL, $adminsettings);
                    $branchname = $adminsettings->churchname;
                    $link = $this->getVerificationLink($email);
                    $subject = "Email Verification";
                    $htmlContent = '<p>Thank you for registering on our platform.</p>';
                    $htmlContent .= '<p>Please click on the link below to verify your email</p>';
                    $this->sendEmail($branchname, $emailconfig, $email, $subject, $this->getActivationEmailTemplate($link, "Verify Email", $htmlContent));
                }
                //var_dump($accountmodel->status); die;
                echo json_encode(array("status" => $accountmodel->status, "message" => $accountmodel->message));
                exit;
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "No data found"));
            exit;
        }
    }

    /**
     * This function used to send reset password link
     */
    public function resetPassword()
    {
        $data = $this->get_data();
        if (!empty($data)) {
            $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH) : "";
            if ($email == "") {
                echo json_encode(array("status" => "error", "message" => "Email Address Is not valid!"));
                exit;
            } else {
                $accountmodel = new accountmodel();
                if ($accountmodel->verifyEmailExists($email) == TRUE) {
                    //if user email exists in the database
                    //send password reset link
                    $settingsmodel = new settingsmodel();
                    $adminsettings = $settingsmodel->getSettings();
                    $emailconfig = $settingsmodel->getEmailConfig(NULL, $adminsettings);
                    $branchname = $adminsettings->churchname;
                    $link = $this->getPasswordResetLink($email);
                    $subject = "Password Reset";
                    $htmlContent = '<p>Please click on the link below to reset your password</p>';
                    $this->sendEmail($branchname, $emailconfig, $email, $subject, $this->getActivationEmailTemplate($link, "Reset Password", $htmlContent));
                }
                echo json_encode(array("status" => "ok", "message" => "If the email exists in our platform, you should recieve an instruction on how to reset your password."));
                exit;
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "No data found"));
            exit;
        }
    }

    //verify email when user clicks on the link
    function verifyEmailLink($code)
    {
        $verifymodel = new verifymodel();
        // Check activation id in database
        $row = $verifymodel->checkActivationDetails($code);
        if ($row) {
            //delete activation details
            $verifymodel->deleteActivationDetails($code);
            //update user to verified
            $accountmodel = new accountmodel();
            $accountmodel->updateUserVerfication($row->email);
            //redirect to message page with message for user
            $data['title'] = 'Congratulations';
            $data['message'] = 'Your account have been successfully verified.';
            return view('success', $data); // this will load the view file
        } else {
            //redirect to message page with message for user
            $data['title'] = 'OOOPS!!!';
            $data['message'] = 'Your email address cannot be verified at the moment.';
            return view('failure', $data); // this will load the view file
        }
    }

    function resetLink($code)
    {
        $verifymodel = new verifymodel();
        // Check activation id in database
        $row = $verifymodel->checkActivationDetails($code);
        if ($row) {
            //redirect to message page with message for user
            $data['email'] = $row->email;
            $data['activation_id'] = $code;
            return view('resetPasswordForm', $data);
        } else {
            //redirect to message page with message for user
            $data['title'] = 'OOOPS!!!';
            $data['message'] = 'Password reset failed. Please try again some other time.';
            return view('failure', $data); // this will load the view file
        }
    }

    //change user password
    public function changeUserPassword()
    {
        $email = $this->request->getVar('email');
        $code = $this->request->getVar('activation_id');
        $password1 = $this->request->getVar('password1');
        $password2 = $this->request->getVar('password2');

        $session = session();
        if ($password1 != $password2) {
            $session->setFlashdata('error', "Passwords dont match");
            $data['email'] = $email;
            $data['activation_id'] = $code;
            return view('resetPasswordForm', $data);
        }

        $verifymodel = new verifymodel();
        $row = $verifymodel->checkActivationDetails($code);
        if (!$row) {
            //redirect to message page with message for user
            $data['title'] = 'OOOPS!!!';
            $data['message'] = 'Password reset failed. Please try again some other time.';
            return view('failure', $data); // this will load the view file
        }

        //
        $accountmodel = new accountmodel();
        $accountmodel->updateUserPassword($email, $password1);
        //delete activation details
        $verifymodel->deleteActivationDetails($code);
        $data['title'] = 'Congratulations';
        $data['message'] = 'Your password reset was successful. You can now login with your new password.';
        return view('success', $data);
    }

    public function updateUserProfile()
    {
        $phonenumber = $this->request->getVar('phonenumber');
        $db = \Config\Database::connect();
        $query = $db->query("SELECT * FROM tbl_members WHERE `phonenumber`='$phonenumber'");
        $results = $query->getResultArray()[0];

        if(empty($results)) {
            echo json_encode(array("status" => "error", "msg" => "Somthing went wrong."));
            exit;
        }

        $name = $this->request->getVar('name');
        $aboutme = !empty($this->request->getVar('aboutme')) ? $this->request->getVar('aboutme') : $results['aboutme'];
        $gender = $this->request->getVar('gender');
        $dob = $this->request->getVar('dob');
        $whatsappnumber = $this->request->getVar('whatsappnumber');
        $villagename = $this->request->getVar('villagename');
        $taluko = $this->request->getVar('taluko');
        $jillo = $this->request->getVar('jillo');
        $occupation = $this->request->getVar('occupation');
        $facebook = !empty($this->request->getVar('facebook')) ? $this->request->getVar('facebook') : $results['facebook'];
        $twitter = !empty($this->request->getVar('twitter')) ? $this->request->getVar('twitter') : $results['twitter'];
        $linkedln = !empty($this->request->getVar('linkedln')) ? $this->request->getVar('linkedln') : $results['linkedln'];
        $info = array(
            'name' => $name,
            'aboutme' => $aboutme,
            'gender' => $gender,
            'name' => $name,
            'dob' => $dob,
            'whatsappnumber' => $whatsappnumber,
            'villagename' => $villagename,
            'taluko' => $taluko,
            'jillo' => $jillo,
            'occupation' => $occupation,
            'facebook' => $facebook,
            'twitter' => $twitter,
            'linkedln' => $linkedln,
        );

        if (!empty($_FILES['avatar'])) {
            //var_dump($_FILES['avatar']);
            $upload = $this->upload_avatar();
            if ($upload[0] == 'ok') {
                $info['thumbnail'] = $upload[1];
            } else {
                echo json_encode(array("status" => "error", "msg" => $upload[1]['avatar']));
                exit;
            }
        }

        if (!empty($_FILES['cover_photo'])) {
            $upload = $this->upload_coverphoto();
            if ($upload[0] == 'ok') {
                $info['coverphoto'] = $upload[1];
            } else {
                echo json_encode(array("status" => "error", "msg" => $upload[1]['cover_photo']));
                exit;
            }
        }

        $accountmodel = new accountmodel();
        $accountmodel->updateUserProfile($info, $phonenumber);
        $user = $accountmodel->getUpdatedUserProfile($phonenumber);
        echo json_encode(array("status" => "ok", "msg" => "Profile was updated successfully", "user" => $user));
        exit;
    }

    function upload_avatar()
    {
        helper(['form', 'url']);
        $input = $this->validate([
            'avatar' => [
                'uploaded[avatar]',
                'mime_in[avatar,image/jpg,image/jpeg,image/png]',
                'max_size[avatar,10024]',
            ]
        ]);
        if (!$input) {
            //$data = ['errors' => $this->validator->getErrors()];
            return ['error', $this->validator->getErrors()];
        } else {
            $img = $this->request->getFile('avatar');
            $img->move('./uploads/members');
            $data = [
                'name' => $img->getName(),
                'type' => $img->getClientMimeType()
            ];
            return ['ok', $img->getName()];
        }
    }

    function upload_coverphoto()
    {
        helper(['form', 'url']);
        $input = $this->validate([
            'cover_photo' => [
                'uploaded[cover_photo]',
                'mime_in[cover_photo,image/jpg,image/jpeg,image/png]',
                'max_size[cover_photo,10024]',
            ]
        ]);
        if (!$input) {
            //$data = ['errors' => $this->validator->getErrors()];
            return ['error', $this->validator->getErrors()];
        } else {
            $img = $this->request->getFile('cover_photo');
            $img->move('./uploads/members');
            $data = [
                'name' => $img->getName(),
                'type' => $img->getClientMimeType()
            ];
            return ['ok', $img->getName()];
        }
    }
}
