<!DOCTYPE html><html lang="en">
<head>
<meta charset="utf-8">
<title>Genyx admin v1.0</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="SuggeElson"/>
<meta name="description" content="Genyx admin template - new premium responsive admin template. This template is designed to help you build the site administration without losing valuable time.Template contains all the important functions which must have one backend system.Build on great twitter boostrap framework"/>
<meta name="keywords" content="admin, admin template, admin theme, responsive, responsive admin, responsive admin template, responsive theme, themeforest, 960 grid system, grid, grid theme, liquid, jquery, administration, administration template, administration theme, mobile, touch , responsive layout, boostrap, twitter boostrap"/>
<meta name="application-name" content="Genyx admin template"/>
<!-- Headings -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800,700' rel='stylesheet' type='text/css'>
<!-- Text -->
<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'/>
<!--[if lt IE 9]> <link href="http://fonts.googleapis.com/css?family=Open+Sans:400" rel="stylesheet" type="text/css" /> <link href="http://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet" type="text/css" /> <link href="http://fonts.googleapis.com/css?family=Open+Sans:800" rel="stylesheet" type="text/css" /> <link href="http://fonts.googleapis.com/css?family=Droid+Sans:400" rel="stylesheet" type="text/css" /> <link href="http://fonts.googleapis.com/css?family=Droid+Sans:700" rel="stylesheet" type="text/css" /> <![endif]-->
<!-- Core stylesheets do not remove -->
<link href="css/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href="css/bootstrap/bootstrap-responsive.css" rel="stylesheet"/>
<link href="css/icons.css" rel="stylesheet"/>
<!-- Plugins stylesheets -->
<link href="js/plugins/forms/uniform/uniform.default.css" rel="stylesheet"/>
<link href="js/plugins/forms/select2/select2.css" rel="stylesheet"/>
<!-- app stylesheets -->
<link href="css/app.css" rel="stylesheet"/>
<!-- Custom stylesheets ( Put your own changes here ) -->
<link href="css/custom.css" rel="stylesheet"/>
<!--[if IE 8]><link href="css/ie8.css" rel="stylesheet" type="text/css" /><![endif]-->
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]> </script><script src="js/html5shiv.js"></script></script> <![endif]-->
<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="images/ico/favicon.png">
<!-- Le javascript ================================================== -->
<!-- Important plugins put in all pages -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="js/bootstrap/bootstrap.js"></script>
<script src="js/conditionizr.min.js"></script>
<script src="js/plugins/core/nicescroll/jquery.nicescroll.min.js"></script>
<script src="js/plugins/core/jrespond/jRespond.min.js"></script>
<script src="js/jquery.genyxAdmin.js"></script>
<!-- Form plugins -->
<script src="js/plugins/forms/uniform/jquery.uniform.min.js"></script>
<script src="js/plugins/forms/validation/jquery.validate.js"></script>
<script src="js/plugins/forms/select2/select2.js"></script>
<!-- Init plugins -->
<script src="js/app.js"></script>
<!-- Core js functions -->
<script src="js/pages/form-validation.js"></script>
<!-- Init plugins only for page -->
</head>
<body>
<header id="header" class="navbar navbar-inverse navbar-fixed-top">
<div class="navbar-inner">
	<div class="container-fluid">
		<a class="brand" href="dashboard.html"><img src="images/logo.png" alt="Genyx admin"></a>
		<div class="nav-no-collapse">
			<div id="top-search">
				<form action="#" method="post" class="form-search">
					<div class="input-append">
						<input type="text" name="tsearch" id="tsearch" placeholder="Search here ..." class="search-query"><button type="submit" class="btn"><i class="icon16 i-search-2 gap-right0"></i></button>
					</div>
				</form>
			</div>
			<ul class="nav pull-right">
				<li class="divider-vertical"></li>
				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon24 i-bell-2"></i><span class="notification red">6</span></a>
				<ul class="dropdown-menu">
					<li><a href="#" class=""><i class="icon16 i-calendar-2"></i> Admin Jenny add event</a></li>
					<li><a href="#" class=""><i class="icon16 i-file-zip"></i> User Dexter attach file</a></li>
					<li><a href="#" class=""><i class="icon16 i-stack-picture"></i> User Dexter attach 3 pictures</a></li>
					<li><a href="#" class=""><i class="icon16 i-cart-add"></i> New orders <span class="notification green">2</span></a></li>
					<li><a href="#" class=""><i class="icon16 i-bubbles-2"></i> New comments <span class="notification red">5</span></a></li>
					<li><a href="#" class=""><i class="icon16 i-pie-5"></i> Daily stats is generated</a></li>
				</ul>
				</li>
				<li class="divider-vertical"></li>
				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon24 i-envelop-2"></i><span class="notification red">3</span></a>
				<ul class="dropdown-menu messages">
					<li class="head">
					<h4>Inbox</h4>
					<span class="count">3 messages</span><span class="new-msg"><a href="#" class="tipB" title="Write message"><i class="icon16 i-pencil-5"></i></a></span></li>
					<li><a href="#" class="clearfix"><span class="avatar"><img src="images/avatars/peter.jpg" alt="avatar"></span><span class="msg">Call me i need to talk with you</span><button class="btn close"><i class="icon12 i-close-2"></i></button></a></li>
					<li><a href="#" class="clearfix"><span class="avatar"><img src="images/avatars/milen.jpg" alt="avatar"></span><span class="msg">Problem with registration</span><button class="btn close"><i class="icon12 i-close-2"></i></button></a></li>
					<li><a href="#" class="clearfix"><span class="avatar"><img src="images/avatars/anonime.jpg" alt="avatar"></span><span class="msg">I have question about ...</span><button class="btn close"><i class="icon12 i-close-2"></i></button></a></li>
					<li class="foot"><a href="email.html">View all messages</a></li>
				</ul>
				</li>
				<li class="divider-vertical"></li>
				<li class="dropdown user"><a href="#" class="dropdown-toggle avatar" data-toggle="dropdown"><img src="images/avatars/sugge.jpg" alt="sugge"><span class="more"><i class="icon16 i-arrow-down-2"></i></span></a>
				<ul class="dropdown-menu">
					<li><a href="#" class=""><i class="icon16 i-cogs"></i> Settings</a></li>
					<li><a href="profile.html" class=""><i class="icon16 i-user"></i> Profile</a></li>
					<li><a href="index.html" class=""><i class="icon16 i-exit"></i> Logout</a></li>
				</ul>
				</li>
				<li class="divider-vertical"></li>
			</ul>
		</div>
		<!--/.nav-collapse -->
	</div>
</div>
</header>
<!-- End #header -->
<div class="main">
	<aside id="sidebar">
	<div class="side-options">
		<ul>
			<li><a href="#" id="collapse-nav" class="act act-primary tip" title="Collapse navigation"><i class="icon16 i-arrow-left-7"></i></a></li>
		</ul>
	</div>
	<div class="sidebar-wrapper">
		<nav id="mainnav">
		<ul class="nav nav-list">
			<li><a href="dashboard.html"><span class="icon"><i class="icon20 i-screen"></i></span><span class="txt">Dashboard</span></a></li>
			<li><a href="charts.html"><span class="icon"><i class="icon20 i-stats-up"></i></span><span class="txt">Charts</span></a></li>
			<li><a href="#"><span class="icon"><i class="icon20 i-menu-6"></i></span><span class="txt">Forms</span></a>
			<ul class="sub">
				<li><a href="form-elements.html"><span class="icon"><i class="icon20 i-stack-list"></i></span><span class="txt">Form elements</span></a></li>
				<li><a href="form-validation.html"><span class="icon"><i class="icon20 i-stack-checkmark"></i></span><span class="txt">Form validation</span></a></li>
				<li><a href="form-wizard.html"><span class="icon"><i class="icon20 i-stack-star"></i></span><span class="txt">Form wizard</span></a></li>
			</ul>
			</li>
			<li><a href="#"><span class="icon"><i class="icon20 i-table-2"></i></span><span class="txt">Tables</span></a>
			<ul class="sub">
				<li><a href="tables.html"><span class="icon"><i class="icon20 i-table"></i></span><span class="txt">Static tables</span></a></li>
				<li><a href="data-tables.html"><span class="icon"><i class="icon20 i-table"></i></span><span class="txt">Data tables</span></a></li>
			</ul>
			</li>
			<li><a href="grid.html"><span class="icon"><i class="icon20 i-grid-5"></i></span><span class="txt">Grid</span></a></li>
			<li><a href="typo.html"><span class="icon"><i class="icon20 i-font"></i></span><span class="txt">Typography</span></a></li>
			<li><a href="calendar.html"><span class="icon"><i class="icon20 i-calendar"></i></span><span class="txt">Calendar</span></a></li>
			<li><a href="#"><span class="icon"><i class="icon20 i-cogs"></i></span><span class="txt">Ui Elements</span></a>
			<ul class="sub">
				<li><a href="icons.html"><span class="icon"><i class="icon20 i-IcoMoon"></i></span><span class="txt">Icons</span></a></li>
				<li><a href="buttons.html"><span class="icon"><i class="icon20 i-point-up"></i></span><span class="txt">Buttons</span></a></li>
				<li><a href="ui-elements.html"><span class="icon"><i class="icon20 i-puzzle"></i></span><span class="txt">UI Elements</span></a></li>
			</ul>
			</li>
			<li><a href="gallery.html"><span class="icon"><i class="icon20 i-images"></i></span><span class="txt">Gallery</span></a></li>
			<li><a href="maps.html"><span class="icon"><i class="icon20 i-location-4"></i></span><span class="txt">Maps</span></a></li>
			<li><a href="file-manager.html"><span class="icon"><i class="icon20 i-cloud-upload"></i></span><span class="txt">File manager</span></a></li>
			<li><a href="widgets.html"><span class="icon"><i class="icon20 i-cube-3"></i></span><span class="txt">Widgets</span></a></li>
			<li><a href="#"><span class="icon"><i class="icon20 i-file-8"></i></span><span class="txt">Pages</span></a>
			<ul class="sub">
				<li><a href="#"><span class="icon"><i class="icon20 i-warning"></i></span><span class="txt">Error Pages</span></a>
				<ul class="sub">
					<li><a href="403.html"><span class="icon"><i class="icon20 i-notification"></i></span><span class="txt">Error 403</span></a></li>
					<li><a href="404.html"><span class="icon"><i class="icon20 i-notification"></i></span><span class="txt">Error 404</span></a></li>
					<li><a href="405.html"><span class="icon"><i class="icon20 i-notification"></i></span><span class="txt">Error 405</span></a></li>
					<li><a href="500.html"><span class="icon"><i class="icon20 i-notification"></i></span><span class="txt">Error 500</span></a></li>
					<li><a href="503.html"><span class="icon"><i class="icon20 i-notification"></i></span><span class="txt">Error 503</span></a></li>
					<li><a href="offline.html"><span class="icon"><i class="icon20 i-notification"></i></span><span class="txt">Offline page</span></a></li>
				</ul>
				</li>
				<li><a href="invoice.html"><span class="icon"><i class="icon20 i-credit"></i></span><span class="txt">Invoice page</span></a></li>
				<li><a href="profile.html"><span class="icon"><i class="icon20 i-user"></i></span><span class="txt">Profile page</span></a></li>
				<li><a href="search.html"><span class="icon"><i class="icon20 i-search-2"></i></span><span class="txt">Search page</span></a></li>
				<li><a href="email.html"><span class="icon"><i class="icon20 i-envelop-2"></i></span><span class="txt">Email page</span></a></li>
				<li><a href="support.html"><span class="icon"><i class="icon20 i-support"></i></span><span class="txt">Support page</span></a></li>
				<li><a href="faq.html"><span class="icon"><i class="icon20 i-question"></i></span><span class="txt">FAQ page</span></a></li>
				<li><a href="blank.html"><span class="icon"><i class="icon20 i-file-7"></i></span><span class="txt">Blank page</span></a></li>
			</ul>
			</li>
		</ul>
		</nav>
		<!-- End #mainnav -->
	</div>
	<!-- End .sidebar-wrapper -->
	</aside>
	<!-- End #sidebar -->
	<section id="content">
	<div class="wrapper">
		<div class="crumb">
			<ul class="breadcrumb">
				<li><a href="#"><i class="icon16 i-home-4"></i>Home</a><span class="divider">/</span></li>
				<li><a href="#">Library</a><span class="divider">/</span></li>
				<li class="active">Data</li>
			</ul>
		</div>
		<div class="container-fluid">
			<div id="heading" class="page-header">
				<h1><i class="icon20 i-list-4"></i> Form validation</h1>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="widget">
						<div class="widget-title">
							<div class="icon">
								<i class="icon20 i-stack-checkmark"></i>
							</div>
							<h4>Form validation</h4>
							<a href="#" class="minimize"></a>
						</div>
						<!-- End .widget-title -->
						<div class="widget-content">
							<form id="validate" class="form-horizontal">
								<div class="control-group">
									<label class="control-label" for="required">Required field</label>
									<div class="controls controls-row">
										<input type="text" id="required" name="required" class="required span12">
									</div>
								</div>
								<!-- End .control-group -->
								<div class="control-group">
									<label class="control-label" for="required">Required with min 6 chars</label>
									<div class="controls controls-row">
										<input type="text" id="required1" name="required1" class="required span12" minlength="6">
									</div>
								</div>
								<!-- End .control-group -->
								<div class="control-group">
									<label class="control-label" for="required">Required with max 10 chars</label>
									<div class="controls controls-row">
										<input type="text" id="maxchar" name="maxchar" class="required span12" maxlength="10">
									</div>
								</div>
								<!-- End .control-group -->
								<div class="control-group">
									<label class="control-label" for="range">Required field with range between 10-20 chars</label>
									<div class="controls controls-row">
										<input class="span12" id="rangelenght" name="rangelenght" type="text"/>
									</div>
								</div>
								<!-- End .control-group -->
								<div class="control-group">
									<label class="control-label" for="minval">Required field with min value of 13</label>
									<div class="controls controls-row">
										<input class="span12" id="minval" name="minval" type="text"/>
									</div>
								</div>
								<!-- End .control-group -->
								<div class="control-group">
									<label class="control-label" for="maxval">Required field with max value of 13</label>
									<div class="controls controls-row">
										<input class="span12" id="maxval" name="maxval" type="text"/>
									</div>
								</div>
								<!-- End .control-group -->
								<div class="control-group">
									<label class="control-label" for="demacial">Required and a decimal number only</label>
									<div class="controls controls-row">
										<input class="span12" id="number" name="number" type="text"/>
									</div>
								</div>
								<!-- End .control-group -->
								<div class="control-group">
									<label class="control-label" for="digits">Required and a digits only</label>
									<div class="controls controls-row">
										<input class="span23" id="digits" name="digits" type="text"/>
									</div>
								</div>
								<!-- End .control-group -->
								<div class="control-group">
									<label class="control-label" for="ccard">Required and accept credit card number</label>
									<div class="controls controls-row">
										<input class="span12" id="ccard" name="ccard" type="text"/>
									</div>
								</div>
								<!-- End .control-group -->
								<div class="control-group">
									<label class="control-label" for="date">Required date</label>
									<div class="controls controls-row">
										<input class="span12" id="date" name="date" type="text"/>
									</div>
								</div>
								<!-- End .control-group -->
								<div class="control-group">
									<label class="control-label" for="url">Url</label>
									<div class="controls controls-row">
										<input id="curl" type="url" name="url" class="required span12"/>
									</div>
								</div>
								<!-- End .control-group -->
								<div class="control-group">
									<label class="control-label" for="required">Required email</label>
									<div class="controls controls-row">
										<input type="text" id="required2" name="required2" class="required email span12">
									</div>
								</div>
								<!-- End .control-group -->
								<div class="control-group">
									<label class="control-label" for="password">Password</label>
									<div class="controls controls-row">
										<input id="password" name="password" type="password" class="span12"/>
									</div>
								</div>
								<!-- End .control-group -->
								<div class="control-group">
									<label class="control-label" for="password1">Confirm password</label>
									<div class="controls controls-row">
										<input id="confirm_password" name="confirm_password" type="password" class="span12"/>
									</div>
								</div>
								<!-- End .control-group -->
								<div class="control-group">
									<label class="control-label" for="agree">Please agree</label>
									<div class="controls controls-row">
										<div class="span12">
											<label class="checkbox inline"><input type="checkbox" id="agree" name="agree"></label>
										</div>
									</div>
								</div>
								<!-- End .control-group -->
								<div class="control-group">
									<label class="control-label" for="textarea">Required textarea</label>
									<div class="controls controls-row">
										<textarea id="textarea" name="textarea" class="span12" rows="5"></textarea>
									</div>
								</div>
								<!-- End .control-group -->
								<div class="control-group">
									<label class="control-label" for="select2">Required select with filter</label>
									<div class="controls controls-row">
										<div class="span6">
											<select name="select1" id="select1" class="select2" style="width:100%;">
												<option></option>
												<optgroup label="Alaskan/Hawaiian Time Zone">
												<option value="AK">Alaska</option>
												<option value="HI">Hawaii</option>
												</optgroup><optgroup label="Pacific Time Zone">
												<option value="CA">California</option>
												<option value="NV">Nevada</option>
												<option value="OR">Oregon</option>
												<option value="WA">Washington</option>
												</optgroup><optgroup label="Mountain Time Zone">
												<option value="AZ">Arizona</option>
												<option value="CO">Colorado</option>
												<option value="ID">Idaho</option>
												<option value="MT">Montana</option>
												<option value="NE">Nebraska</option>
												<option value="NM">New Mexico</option>
												<option value="ND">North Dakota</option>
												<option value="UT">Utah</option>
												<option value="WY">Wyoming</option>
												</optgroup><optgroup label="Central Time Zone">
												<option value="AL">Alabama</option>
												<option value="AR">Arkansas</option>
												<option value="IL">Illinois</option>
												<option value="IA">Iowa</option>
												<option value="KS">Kansas</option>
												<option value="KY">Kentucky</option>
												<option value="LA">Louisiana</option>
												<option value="MN">Minnesota</option>
												<option value="MS">Mississippi</option>
												<option value="MO">Missouri</option>
												<option value="OK">Oklahoma</option>
												<option value="SD">South Dakota</option>
												<option value="TX">Texas</option>
												<option value="TN">Tennessee</option>
												<option value="WI">Wisconsin</option>
												</optgroup><optgroup label="Eastern Time Zone">
												<option value="CT">Connecticut</option>
												<option value="DE">Delaware</option>
												<option value="FL">Florida</option>
												<option value="GA">Georgia</option>
												<option value="IN">Indiana</option>
												<option value="ME">Maine</option>
												<option value="MD">Maryland</option>
												<option value="MA">Massachusetts</option>
												<option value="MI">Michigan</option>
												<option value="NH">New Hampshire</option>
												<option value="NJ">New Jersey</option>
												<option value="NY">New York</option>
												<option value="NC">North Carolina</option>
												<option value="OH">Ohio</option>
												<option value="PA">Pennsylvania</option>
												<option value="RI">Rhode Island</option>
												<option value="SC">South Carolina</option>
												<option value="VT">Vermont</option>
												<option value="VA">Virginia</option>
												<option value="WV">West Virginia</option>
												</optgroup>
											</select>
										</div>
										<!-- End .span6 -->
									</div>
								</div>
								<!-- End .control-group -->
								<div class="form-actions">
									<button type="submit" class="btn btn-primary">Save changes</button><button type="button" class="btn">Cancel</button>
								</div>
							</form>
						</div>
						<!-- End .widget-content -->
					</div>
					<!-- End .widget -->
				</div>
				<!-- End .span12 -->
			</div>
			<!-- End .row-fluid -->
		</div>
		<!-- End .container-fluid -->
	</div>
	<!-- End .wrapper -->
	</section>
</div>
<!-- End .main -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-3560057-22', 'suggeelson.com');
  ga('send', 'pageview');
</script>
</body>
</html>