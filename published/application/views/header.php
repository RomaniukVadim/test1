<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?= ($page_title) ? $page_title : "12Bet - CAL"; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
        <meta name="ROBOTS" content="NOARCHIVE">
        <!-- Headings -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800,700' rel='stylesheet' type='text/css'>
        <!-- Text -->
        <link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'/>
        <!--[if lt IE 9]> <link href="http://fonts.googleapis.com/css?family=Open+Sans:400" rel="stylesheet" type="text/css" /> <link href="http://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet" type="text/css" /> <link href="http://fonts.googleapis.com/css?family=Open+Sans:800" rel="stylesheet" type="text/css" /> <link href="http://fonts.googleapis.com/css?family=Droid+Sans:400" rel="stylesheet" type="text/css" /> <link href="http://fonts.googleapis.com/css?family=Droid+Sans:700" rel="stylesheet" type="text/css" /> <![endif]--> 

        <!-- Core stylesheets do not remove -->
        <link href="<?= base_url() ?>media/css/bootstrap/bootstrap.css" rel="stylesheet"/>
        <link href="<?= base_url() ?>media/css/bootstrap/bootstrap-responsive.css" rel="stylesheet"/>
        <link href="<?= base_url() ?>media/css/icons.css" rel="stylesheet"/> 

        <!-- Plugins stylesheets -->
        <link href="<?= base_url() ?>media/js/plugins/forms/uniform/uniform.default.css" rel="stylesheet"/>
        <link href="<?= base_url() ?>media/js/plugins/tables/datatables/jquery.dataTables.css" rel="stylesheet"/> 
        <link href="<?= base_url() ?>media/css/jquery.jgrowl.css" rel="stylesheet"/> 

        <!-- app stylesheets -->
        <link href="<?= base_url() ?>media/css/app.css" rel="stylesheet"/> 

        <!-- Custom stylesheets ( Put your own changes here ) -->
        <link href="<?= base_url() ?>media/css/custom.css" rel="stylesheet"/>
        
     
        <!--[if IE 8]><link href="css/ie8.css" rel="stylesheet" type="text/css" /><![endif]-->
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]> </script><script src="js/html5shiv.js"></script></script> <![endif]--> 

        <!-- Fav and touch icons -->
        <link rel="apple-touch-icon" sizes="57x57" href="<?=base_url()?>media/images/ico/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?=base_url()?>media/images/ico/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?=base_url()?>media/images/ico/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?=base_url()?>media/images/ico/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?=base_url()?>media/images/ico/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?=base_url()?>media/images/ico/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?=base_url()?>media/images/ico/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?=base_url()?>media/images/ico/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?=base_url()?>/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192" href="<?=base_url()?>media/images/ico/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?=base_url()?>media/images/ico/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="<?=base_url()?>media/images/ico/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url()?>media/images/ico/favicon-16x16.png">
        <link rel="manifest" href="<?=base_url()?>media/images/ico/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="<?=base_url()?>media/images/ico/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">   

        <!-- javascript ================================================== -->
        <script>
            var base_url = "<?= base_url() ?>";
            var xhr = "";
        </script>

        <!-- Important plugins put in all pages -->
        <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->
        <script src="<?= base_url() ?>media/js/jquery.min.js"></script>
        <!-- Jquery UI -->
        <script src="<?= base_url() ?>media/js/jquery-ui/jquery-ui.min.js"></script>
        <link href="<?= base_url() ?>media/js/jquery-ui/jquery-ui.min.css" rel="stylesheet"/> 


        <script src="<?= base_url() ?>media/js/bootstrap/bootstrap.js"></script>
        <script src="<?= base_url() ?>media/js/conditionizr.min.js"></script> 

        <script src="<?= base_url() ?>media/js/plugins/core/nicescroll/jquery.nicescroll.min.js"></script>
        <script src="<?= base_url() ?>media/js/plugins/core/jrespond/jRespond.min.js"></script>
        <script src="<?= base_url() ?>media/js/jquery.genyxAdmin.js"></script>

        <!-- Form plugins -->
        <script src="<?= base_url() ?>media/js/plugins/forms/uniform/jquery.uniform.min.js"></script>

        <script src="<?= base_url() ?>media/js/plugins/ui/jgrowl/jquery.jgrowl.min.js"></script>

        <!-- Init plugins -->
        <script src="<?= base_url() ?>media/js/app.js"></script>
        <!-- Core js functions --> 
        <script src="<?= base_url() ?>media/js/jquery.cookie.js"></script> 
 
        <script src="<?= base_url() ?>media/js/common.js?v=2"></script>
 

        <?php
        if ($settings[bodybg]) {
            ?>
            <?php /* ?><style>
              .bg {

              background:url() no-repeat center center !important;
              background-size: cover !important;
              width: 100% !important;
              height: 100% !important;

              }
              </style><?php */ ?>
            <?php
        }
        ?>

        <link href="<?= base_url() ?>media/css/mythemes.css" rel="stylesheet"/>

    </head>
    <body class="bg" id=""  >

        <script>
            //$.cookie("test", 1); 
            $("body").attr("id", localStorage.getItem('SelectedScheme'));
            //localStorage.setItem('SelectedScheme', 1); 
        </script>