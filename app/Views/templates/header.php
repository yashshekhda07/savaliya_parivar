<?php
$url = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>Savaliya parivar admin panel</title>

	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>/public/assets/vendors/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>/public/assets/vendors/images/favicon.ico">

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/src/plugins/datatables/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/src/plugins/datatables/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/vendors/styles/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/vendors/styles/style.css">
	<link type="text/css" href="<?php echo base_url(); ?>/public/assets/vendors/sweetalert/sweetalert.css" rel="stylesheet">
	<link type="text/css" href="<?php echo base_url(); ?>/public/assets/vendors/dropify/dist/css/dropify.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/assets/src/plugins/dropzone/src/dropzone.css">
	<script src='<?= base_url() ?>/public/assets/tinymce/tinymce.js'></script>

    <style>
        .skiptranslate{
            display:none;
        }
    </style>

</head>
<script type="text/javascript">
	var baseURL = "<?php echo base_url(); ?>";
</script>
<?php $session = session(); ?>
<body>
	<div class="header">
		<div class="header-left">
			<div class="menu-icon dw dw-menu"></div>
		</div>
		<div class="header-right">
			<div class="user-info-dropdown">
				<div class="dropdown">
					<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
						<span class="user-icon" style="visibility: hidden;">
							<i class="fa fa-user"></i>
						</span>
						<span class="user-name"><?php echo $session->get('name'); ?></span>
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
						<a class="dropdown-item" href="logout"><i class="dw dw-logout"></i> Log Out</a>
					</div>
				</div>
			</div>

		</div>
	</div>


	<div class="left-side-bar">
		<div class="brand-logo">
			<a href="">
				<h4 style="color:white;">Savaliya parivar</h4>
			</a>
			<div class="close-sidebar" data-toggle="left-sidebar-close">
				<i class="ion-close-round"></i>
			</div>
		</div>
		<div class="menu-block customscroll">
			<div class="sidebar-menu">
				<ul id="accordion-menu">
					<li>
						<a href="<?php echo base_url(); ?>/dashboard" class="dropdown-toggle no-arrow <?php if (strpos(strtolower($url),'dashboard') !== false){ ?> active <?php } ?>">
							<span class="micon dw dw-home"></span><span class="mtext">Dashboard</span>
						</a>
					</li>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle <?php if (strpos(strtolower($url),'members') !== false){ ?> active <?php } ?>">
							<span class="micon fi-torsos-all"></span><span class="mtext">Members</span>
						</a>
						<ul class="submenu">
							<li><a href="<?php echo base_url(); ?>/membersListing" <?php if (strpos(strtolower($url),'members') !== false){ ?> class="active" <?php } ?>>All Members</a></li>
							<!-- <li><a href="<?php echo base_url(); ?>/lists" <?php if (strpos(strtolower($url),'list') !== false){ ?> class="active" <?php } ?>>Email/SMS Lists</a></li> -->
						</ul>
					</li>
					<!-- <li>
						<a href="<?php echo base_url(); ?>/donations" class="dropdown-toggle no-arrow <?php if (strpos(strtolower($url),'donation') !== false){ ?> active <?php } ?>" >
							<span class="micon dw dw-wallet1"></span><span class="mtext">Donations</span>
						</a>
					</li> -->
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-video-camera"></span><span class="mtext">Media</span>
						</a>
						<ul class="submenu">
							<li>
								<a href="<?php echo base_url(); ?>/videos" <?php if (strpos(strtolower($url),'video') !== false){ ?> class="active" <?php } ?>>Videos</a>
							</li>
							<li>
								<a href="<?php echo base_url(); ?>/audios" <?php if (strpos(strtolower($url),'audio') !== false){ ?> class="active" <?php } ?>>Audios</a>
							</li>
							<!-- <li>
								<a href="<?php echo base_url(); ?>/livestreams" <?php if (strpos(strtolower($url),'livestream') !== false){ ?> class="active" <?php } ?>>Livestream</a>
							</li>
							<li>
								<a href="<?php echo base_url(); ?>/radio" <?php if (strpos(strtolower($url),'radio') !== false){ ?> class="active" <?php } ?>>Radio</a>
							</li> -->
							<li>
								<a href="<?php echo base_url(); ?>/photos" <?php if (strpos(strtolower($url),'photo') !== false){ ?> class="active" <?php } ?>>Photos</a>
							</li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-books"></span><span class="mtext">Books</span>
						</a>
						<ul class="submenu">
							<!-- <li><a href="<?php echo base_url(); ?>/devotionalsListing" <?php if (strpos(strtolower($url),'devotional') !== false){ ?> class="active" <?php } ?>>Devotionals</a></li> -->
							<li><a href="<?php echo base_url(); ?>/books" <?php if (strpos(strtolower($url),'book') !== false){ ?> class="active" <?php } ?>>Books</a></li>
							<!-- <li><a href="<?php echo base_url(); ?>/articlesListing" <?php if (strpos(strtolower($url),'article') !== false){ ?> class="active" <?php } ?>>Articles</a></li> -->
						</ul>
					</li>
					<!-- <li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-group"></span><span class="mtext">Connect</span>
						</a>
						<ul class="submenu">
							<li><a href="<?php echo base_url(); ?>/groups" <?php if (strpos(strtolower($url),'group') !== false){ ?> class="active" <?php } ?>>Groups</a></li>
							<li><a href="<?php echo base_url(); ?>/prayersListing" <?php if (strpos($url,'prayer') !== false || strpos(strtolower($url),'Prayer') !== false){ ?> class="active" <?php } ?>>Prayers</a></li>
							<li><a href="<?php echo base_url(); ?>/testimonyListing" <?php if (strpos(strtolower($url),'testimo') !== false){ ?> class="active" <?php } ?>>Testimonies</a></li>
						</ul>
					</li> -->
					<li>
						<a href="<?php echo base_url(); ?>/eventsListing" class="dropdown-toggle no-arrow <?php if (strpos(strtolower($url),'event') !== false){ ?> active <?php } ?>" >
							<span class="micon dw dw-calendar1"></span><span class="mtext">Meetings</span>
						</a>
					</li>
					<?php if($session->get('role') == 0){ ?>
						<!-- <li>
							<a href="<?php echo base_url(); ?>/hymnsListing" class="dropdown-toggle no-arrow <?php if (strpos(strtolower($url),'hymn') !== false){ ?> active <?php } ?>" >
								<span class="micon dw dw-open-book"></span><span class="mtext">Hymns</span>
							</a>
						</li> -->
					<?php } ?>

					<!-- <li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-email"></span><span class="mtext">Messaging</span>
						</a>
						<ul class="submenu">
							<li><a href="<?php echo base_url(); ?>/messaging" <?php if (strpos(strtolower($url),'messag') !== false){ ?> class="active" <?php } ?>>Email/SMS</a></li>
							<li><a href="<?php echo base_url(); ?>/inbox" <?php if (strpos($url,'inbox') !== false || strpos(strtolower($url),'inbox') !== false){ ?> class="active" <?php } ?>>Notifications</a></li>
						</ul>
					</li> -->
					<?php if($session->get('role') == 0){ ?>
						<li>
							<a href="<?php echo base_url(); ?>/branchesListing" class="dropdown-toggle no-arrow <?php if (strpos(strtolower($url),'branch') !== false){ ?> active <?php } ?>" >
								<span class="micon dw dw-city"></span><span class="mtext">Office location</span>
							</a>
						</li>
					<?php } ?>
					<li>
						<a href="<?php echo base_url(); ?>/settings" class="dropdown-toggle no-arrow <?php if (strpos(strtolower($url),'settings') !== false){ ?> active <?php } ?>" >
							<span class="micon dw dw-settings"></span><span class="mtext">Settings</span>
						</a>
					</li>
					<?php if($session->get('role') == 0){ ?>
						<li>
							<a href="<?php echo base_url(); ?>/adminListing" class="dropdown-toggle no-arrow <?php if (strpos(strtolower($url),'admin') !== false){ ?> active <?php } ?>">
								<span class="micon dw dw-user1"></span><span class="mtext">Admin Users</span>
							</a>
						</li>
					<?php } ?>

				</ul>
			</div>
		</div>
	</div>
	<div class="mobile-menu-overlay"></div>
