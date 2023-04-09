<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('login', 'Login::index', ["filter" => "noauth"]);
$routes->post('authenticate', 'Login::authenticate');
$routes->get('/', 'Home::index', ['filter' => 'auth']);
$routes->get('dashboard', 'Home::index', ['filter' => 'auth']);
$routes->get('logout', 'Login::logout');
//audios
$routes->get('audios', 'Audios::index', ['filter' => 'auth']);
$routes->post('fetchAudios', 'Audios::fetch', ['filter' => 'auth']);
$routes->get('newaudio', 'Audios::newAudio', ['filter' => 'auth']);
$routes->post('saveNewAudio', 'Audios::saveNewAudio', ['filter' => 'auth']);
$routes->get('editAudio/(:any)', 'Audios::editAudio/$1', ['filter' => 'auth']);
$routes->post('editAudioData', 'Audios::editAudioData', ['filter' => 'auth']);
$routes->get('deleteAudio/(:any)', 'Audios::deleteAudio/$1', ['filter' => 'auth']);

//branches
$routes->get('branchesListing', 'Branches::index', ['filter' => 'auth']);
$routes->get('newBranch', 'Branches::newBranch', ['filter' => 'auth']);
$routes->post('savenewbranch', 'Branches::savenewbranch', ['filter' => 'auth']);
$routes->get('editBranch/(:any)', 'Branches::editBranch/$1', ['filter' => 'auth']);
$routes->post('editBranchData', 'Branches::editBranchData', ['filter' => 'auth']);
$routes->get('deleteBranch/(:any)', 'Branches::deleteBranch/$1', ['filter' => 'auth']);

//video
$routes->get('videos', 'Videos::index', ['filter' => 'auth']);
$routes->post('fetchVideos', 'Videos::fetch', ['filter' => 'auth']);
$routes->get('newVideo', 'Videos::newVideo', ['filter' => 'auth']);
$routes->post('saveNewVideo', 'Videos::saveNewVideo', ['filter' => 'auth']);
$routes->get('editVideo/(:any)', 'Videos::editVideo/$1', ['filter' => 'auth']);
$routes->post('editVideoData', 'Videos::editVideoData', ['filter' => 'auth']);
$routes->get('deleteVideo/(:any)', 'Videos::deleteVideo/$1', ['filter' => 'auth']);

//livestream
$routes->get('livestreams', 'Livestream::index', ['filter' => 'auth']);
$routes->get('newLivestream', 'Livestream::newLivestream', ['filter' => 'auth']);
$routes->post('savenewlivestream', 'Livestream::savenewlivestream', ['filter' => 'auth']);
$routes->get('editLivestream/(:any)', 'Livestream::editLivestream/$1', ['filter' => 'auth']);
$routes->post('editLivestreamData', 'Livestream::editLivestreamData', ['filter' => 'auth']);
$routes->get('deleteLivestream/(:any)', 'Livestream::deleteLivestream/$1', ['filter' => 'auth']);

//livestream
$routes->get('radio', 'Radio::index', ['filter' => 'auth']);
$routes->get('newRadio', 'Radio::newRadio', ['filter' => 'auth']);
$routes->post('savenewradio', 'Radio::savenewradio', ['filter' => 'auth']);
$routes->get('editRadio/(:any)', 'Radio::editRadio/$1', ['filter' => 'auth']);
$routes->post('editRadioData', 'Radio::editRadioData', ['filter' => 'auth']);
$routes->get('deleteRadio/(:any)', 'Radio::deleteRadio/$1', ['filter' => 'auth']);

//livestream
$routes->get('photos', 'Photos::index', ['filter' => 'auth']);
$routes->get('newPhotos', 'Photos::newPhotos', ['filter' => 'auth']);
$routes->post('savenewphoto', 'Photos::savenewphoto', ['filter' => 'auth']);
$routes->get('deletePhoto/(:any)', 'Photos::deletePhoto/$1', ['filter' => 'auth']);
$routes->get('editPhoto/(:any)', 'Photos::editPhoto/$1', ['filter' => 'auth']);
$routes->post('editPhotoData', 'Photos::editPhotoData', ['filter' => 'auth']);

//admin users
$routes->get('adminListing', 'User::index', ['filter' => 'auth']);
$routes->get('newAdmin', 'User::newAdmin', ['filter' => 'auth']);
$routes->post('savenewadmin', 'User::savenewadmin', ['filter' => 'auth']);
$routes->get('editAdmin/(:any)', 'User::editAdmin/$1', ['filter' => 'auth']);
$routes->post('editadmindata', 'User::editadmindata', ['filter' => 'auth']);
$routes->get('deleteAdmin/(:any)', 'User::deleteAdmin/$1', ['filter' => 'auth']);

//settings
$routes->get('settings', 'Settings::index', ['filter' => 'auth']);
$routes->post('updatesettings', 'Settings::updatesettings', ['filter' => 'auth']);

//devotionals
$routes->post('getDevotionals', 'Devotionals::getDevotionals', ['filter' => 'auth']);
$routes->get('devotionalsListing', 'Devotionals::index', ['filter' => 'auth']);
$routes->get('newDevotional', 'Devotionals::newDevotional', ['filter' => 'auth']);
$routes->post('saveNewDevotional', 'Devotionals::saveNewDevotional', ['filter' => 'auth']);
$routes->get('editDevotional/(:any)', 'Devotionals::editDevotional/$1', ['filter' => 'auth']);
$routes->post('editDevotionalData', 'Devotionals::editDevotionalData', ['filter' => 'auth']);
$routes->get('deleteDevotional/(:any)', 'Devotionals::deleteDevotional/$1', ['filter' => 'auth']);

//members
$routes->post('getMembers', 'Members::getMembers', ['filter' => 'auth']);
$routes->get('membersListing', 'Members::index', ['filter' => 'auth']);
$routes->get('newMember', 'Members::newMember', ['filter' => 'auth']);
$routes->post('saveNewMember', 'Members::saveNewMember', ['filter' => 'auth']);
$routes->get('editMember/(:any)', 'Members::editMember/$1', ['filter' => 'auth']);
$routes->post('editMemberData', 'Members::editMemberData', ['filter' => 'auth']);
$routes->get('deleteMember/(:any)', 'Members::deleteMember/$1', ['filter' => 'auth']);

//articles
$routes->post('getArticles', 'Articles::getArticles', ['filter' => 'auth']);
$routes->get('articlesListing', 'Articles::index', ['filter' => 'auth']);
$routes->get('newArticle', 'Articles::newArticle', ['filter' => 'auth']);
$routes->post('saveNewArticle', 'Articles::saveNewArticle', ['filter' => 'auth']);
$routes->get('editArticle/(:any)', 'Articles::editArticle/$1', ['filter' => 'auth']);
$routes->post('editArticleData', 'Articles::editArticleData', ['filter' => 'auth']);
$routes->get('deleteArticle/(:any)', 'Articles::deleteArticle/$1', ['filter' => 'auth']);

//events
//$routes->post('getDevotionals', 'Events::getDevotionals', ['filter' => 'auth']);
$routes->get('eventsListing', 'Events::index', ['filter' => 'auth']);
$routes->get('newEvent', 'Events::newEvent', ['filter' => 'auth']);
$routes->post('savenewevent', 'Events::savenewevent', ['filter' => 'auth']);
$routes->get('editEvent/(:any)', 'Events::editEvent/$1', ['filter' => 'auth']);
$routes->post('editEventData', 'Events::editEventData', ['filter' => 'auth']);
$routes->get('deleteEvent/(:any)', 'Events::deleteEvent/$1', ['filter' => 'auth']);

//hymns
$routes->post('getHymns', 'Hymns::getHymns', ['filter' => 'auth']);
$routes->get('hymnsListing', 'Hymns::index', ['filter' => 'auth']);
$routes->get('newHymn', 'Hymns::newHymn', ['filter' => 'auth']);
$routes->post('saveNewHymn', 'Hymns::saveNewHymn', ['filter' => 'auth']);
$routes->get('editHymn/(:any)', 'Hymns::editHymn/$1', ['filter' => 'auth']);
$routes->post('editHymnData', 'Hymns::editHymnData', ['filter' => 'auth']);
$routes->get('deleteHymn/(:any)', 'Hymns::deleteHymn/$1', ['filter' => 'auth']);

//lists
$routes->get('lists', 'Lists::index', ['filter' => 'auth']);
$routes->get('newList', 'Lists::newList', ['filter' => 'auth']);
$routes->post('savenewlist', 'Lists::savenewlist', ['filter' => 'auth']);
$routes->get('editList/(:any)', 'Lists::editList/$1', ['filter' => 'auth']);
$routes->post('editListData', 'Lists::editListData', ['filter' => 'auth']);
$routes->get('deleteList/(:any)', 'Lists::deleteList/$1', ['filter' => 'auth']);
$routes->get('viewListMembers/(:any)', 'Lists::viewListMembers/$1', ['filter' => 'auth']);
$routes->get('addMemberstoList/(:any)', 'Lists::addMemberstoList/$1', ['filter' => 'auth']);
$routes->get('removeFromList/(:any)/(:any)', 'Lists::removeFromList/$1/$1', ['filter' => 'auth']);
$routes->post('savenewmemberslist', 'Lists::savenewmemberslist', ['filter' => 'auth']);
$routes->get('fetchlists/(:any)', 'Lists::fetchlists/$1', ['filter' => 'auth']);

//testimonies
$routes->get('testimonyListing', 'Testimony::index', ['filter' => 'auth']);
$routes->get('newTestimony', 'Testimony::newTestimony', ['filter' => 'auth']);
$routes->post('savenewtestimony', 'Testimony::savenewtestimony', ['filter' => 'auth']);
$routes->get('editTestimony/(:any)', 'Testimony::editTestimony/$1', ['filter' => 'auth']);
$routes->post('edittestimonydata', 'Testimony::edittestimonydata', ['filter' => 'auth']);
$routes->get('deleteTestimony/(:any)', 'Testimony::deleteTestimony/$1', ['filter' => 'auth']);
$routes->get('editTestimonyStatus/(:any)/(:any)', 'Testimony::editTestimonyStatus/$1/$2', ['filter' => 'auth']);

//prayers
$routes->get('prayersListing', 'Prayers::index', ['filter' => 'auth']);
$routes->get('newPrayer', 'Prayers::newPrayer', ['filter' => 'auth']);
$routes->post('savenewprayer', 'Prayers::savenewprayer', ['filter' => 'auth']);
$routes->get('editPrayer/(:any)', 'Prayers::editPrayer/$1', ['filter' => 'auth']);
$routes->post('editprayerdata', 'Prayers::editprayerdata', ['filter' => 'auth']);
$routes->get('deletePrayer/(:any)', 'Prayers::deletePrayer/$1', ['filter' => 'auth']);
$routes->get('editPrayerStatus/(:any)/(:any)', 'Prayers::editPrayerStatus/$1/$2', ['filter' => 'auth']);

//groups
$routes->get('groups', 'Groups::index', ['filter' => 'auth']);
$routes->get('newGroup', 'Groups::newGroup', ['filter' => 'auth']);
$routes->post('savenewgroup', 'Groups::savenewgroup', ['filter' => 'auth']);
$routes->get('editGroup/(:any)', 'Groups::editGroup/$1', ['filter' => 'auth']);
$routes->post('editGroupData', 'Groups::editGroupData', ['filter' => 'auth']);
$routes->get('deleteGroup/(:any)', 'Groups::deleteGroup/$1', ['filter' => 'auth']);
$routes->get('viewGroupMembers/(:any)', 'Groups::viewGroupMembers/$1', ['filter' => 'auth']);
$routes->get('addMemberstoGroup/(:any)', 'Groups::addMemberstoGroup/$1', ['filter' => 'auth']);
$routes->get('removeFromGroup/(:any)/(:any)', 'Groups::removeFromGroup/$1/$2', ['filter' => 'auth']);
$routes->post('savenewmembersgroup', 'Groups::savenewmembersgroup', ['filter' => 'auth']);
$routes->get('groupEvents/(:any)', 'Groups::groupEvents/$1', ['filter' => 'auth']);
$routes->get('editGroupMemberStatus/(:any)/(:any)', 'Groups::editGroupMemberStatus/$1/$2', ['filter' => 'auth']);


$routes->get('newGroupEvent/(:any)', 'Groups::newEvent/$1', ['filter' => 'auth']);
$routes->post('savenewgroupevent', 'Groups::savenewevent', ['filter' => 'auth']);
$routes->get('editGroupEvent/(:any)', 'Groups::editEvent/$1', ['filter' => 'auth']);
$routes->post('editGroupEventData', 'Groups::editEventData', ['filter' => 'auth']);
$routes->get('deleteGroupEvent/(:any)', 'Groups::deleteEvent/$1', ['filter' => 'auth']);

//donations
$routes->get('donations', 'Donations::index', ['filter' => 'auth']);
$routes->post('donationslisting', 'Donations::donationslisting', ['filter' => 'auth']);
$routes->get('donate', 'Donations::donate');
$routes->post('savedonation', 'Donations::savedonation');
$routes->get('thank_you', 'Donations::thank_you');

//messaging
$routes->get('messaging', 'Messaging::index', ['filter' => 'auth']);
$routes->get('newMessage', 'Messaging::newMessage', ['filter' => 'auth']);
$routes->post('sendnewmessage', 'Messaging::sendnewmessage', ['filter' => 'auth']);
$routes->get('editMessage/(:any)', 'Messaging::editMessage/$1', ['filter' => 'auth']);
$routes->post('editMessageData', 'Messaging::editMessageData', ['filter' => 'auth']);
$routes->get('deleteMessage/(:any)', 'Messaging::deleteMessage/$1', ['filter' => 'auth']);
$routes->get('resendMessage/(:any)', 'Messaging::resendMessage/$1', ['filter' => 'auth']);

//messaging
$routes->get('inbox', 'Inbox::index', ['filter' => 'auth']);
$routes->get('newInbox', 'Inbox::newInbox', ['filter' => 'auth']);
$routes->post('sendnewinbox', 'Inbox::sendnewinbox', ['filter' => 'auth']);
$routes->get('editInbox/(:any)', 'Inbox::editInbox/$1', ['filter' => 'auth']);
$routes->post('editInboxData', 'Inbox::editInboxData', ['filter' => 'auth']);
$routes->get('deleteInbox/(:any)', 'Inbox::deleteInbox/$1', ['filter' => 'auth']);
$routes->get('resendInbox/(:any)', 'Inbox::resendInbox/$1', ['filter' => 'auth']);

//books
$routes->get('books', 'Books::index', ['filter' => 'auth']);
$routes->get('newBook', 'Books::newBook', ['filter' => 'auth']);
$routes->post('saveNewBook', 'Books::saveNewBook', ['filter' => 'auth']);
$routes->get('editBook/(:any)', 'Books::editBook/$1', ['filter' => 'auth']);
$routes->post('editBookData', 'Books::editBookData', ['filter' => 'auth']);
$routes->get('deleteBook/(:any)', 'Books::deleteBook/$1', ['filter' => 'auth']);

//api routes
$routes->post('storefcmtoken', 'Api::storeFcmToken');
$routes->post('initapp', 'Api::initapp');
//acount
$routes->post('loginapp', 'Api::loginapp');
$routes->post('createaccount', 'Api::createaccount');
$routes->post('resendVerificationMail', 'Api::resendVerificationMail');
$routes->post('resetpassword', 'Api::resetpassword');
$routes->get('resetLink/(:any)', 'Api::resetLink/$1');
$routes->get('verifyEmailLink/(:any)', 'Api::verifyEmailLink/$1');
$routes->post('changeUserPassword', 'Api::changeUserPassword');
$routes->post('updateUserProfile', 'Api::updateUserProfile');
$routes->post('deletemyaccount', 'Api::deletemyaccount');
//others
$routes->post('getitemdata', 'Api::getitemdata');
$routes->post('fetchmedia', 'Api::fetchmedia');
$routes->post('fetchphotos', 'Api::fetchphotos');
$routes->post('fetchradios', 'Api::fetchradios');
$routes->post('fetchlivestreams', 'Api::fetchlivestreams');
$routes->post('fetchbooks', 'Api::fetchbooks');
$routes->post('fetcharticles', 'Api::fetcharticles');
$routes->post('fetchbranches', 'Api::fetchbranches');
$routes->post('fetchgroups', 'Api::fetchgroups');
$routes->post('fetchgroupevents', 'Api::fetchgroupevents');
$routes->post('fetchmygroups', 'Api::fetchmygroups');
$routes->post('joingroup', 'Api::joingroup');
$routes->post('fetchprayers', 'Api::fetchprayers');
$routes->post('fetchtestimonies', 'Api::fetchtestimonies');
$routes->post('submittestimony', 'Api::submittestimony');
$routes->post('submitprayer', 'Api::submitprayer');
$routes->post('update_media_total_views', 'Api::update_media_total_views');
$routes->post('getBibleVersions' , 'Api::getBibleVersions');
$routes->post('fetch_events', 'Api::fetch_events');
$routes->post('fetch_devotionals', 'Api::fetch_devotionals');
$routes->post('fetch_hymns', 'Api::fetch_hymns');
$routes->post('fetch_inbox', 'Api::fetch_inbox');
$routes->post('search', 'Api::search');

//socials and chats
$routes->post('updateUserSocialFcmToken', "Socials::updateUserSocialFcmToken");
$routes->post('make_post', 'Socials::make_post');
$routes->post('fetch_posts', 'Socials::fetch_posts');
$routes->post('likeunlikepost', "Socials::likeunlikepost");
$routes->post('pinunpinpost', "Socials::pinunpinpost");
$routes->post('editpost', "Socials::editpost");
$routes->post('deletepost', "Socials::deletepost");
$routes->post('post_likes_people', "Socials::post_likes_people");

$routes->post('makepostcomment', "Socials::makecomment");
$routes->post('editpostcomment', "Socials::editcomment");
$routes->post('deletepostcomment', "Socials::deletecomment");
$routes->post('loadpostcomments', "Socials::loadcomments");
$routes->post('reportpostcomment', "Socials::reportcomment");
$routes->post('replypostcomment', "Socials::replycomment");
$routes->post('editpostreply', "Socials::editreply");
$routes->post('deletepostreply', "Socials::deletereply");
$routes->post('loadpostreplies', "Socials::loadreplies");

$routes->post('get_users_to_follow', "Socials::get_users_to_follow");
$routes->post('userNotifications', "Socials::userNotifications");
$routes->post('deleteNotification', "Socials::deleteNotification");
$routes->post('setSeenNotifications', "Socials::setSeenNotifications");
$routes->post('getUnSeenNotifications', "Socials::getUnSeenNotifications");
$routes->post('userBioInfo', "Socials::userBioInfo");
$routes->post('fetch_user_settings', "Socials::fetch_user_settings");
$routes->post('update_user_settings', "Socials::update_user_settings");
$routes->post('fetchUserPins', "Socials::fetchUserPins");

//chat
$routes->post('fetch_user_chats', 'Chat::fetch_user_chats');
$routes->post('load_more_chats', 'Chat::load_more_chats');
$routes->post('fetch_user_partner_chat', 'Chat::fetch_user_partner_chat');
$routes->post('save_user_conversation', 'Chat::save_user_conversation');
$routes->post('on_seen_conversation', 'Chat::on_seen_conversation');
$routes->post('on_user_typing', 'Chat::on_user_typing');
$routes->post('update_user_online_status', 'Chat::update_user_online_status');
$routes->post('delete_selected_chat_messages', 'Chat::delete_selected_chat_messages');
$routes->post('clear_user_conversation', 'Chat::clear_user_conversation');
$routes->post('blockUnblockUser', 'Chat::blockUnblockUser');
$routes->post('checkfornewmessages', 'Chat::checkfornewmessages');


//other pages
$routes->get('terms', 'Settings::terms');
$routes->get('privacy', 'Settings::privacy');
$routes->get('aboutus', 'Settings::aboutus');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
