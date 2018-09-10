{config_load file="web/templates/frontend/default/lang/{$website_lang}/layout.php"}

<!DOCTYPE html>
<html lang="{$website_lang}">
	<head>
		<title>{block name=title}{/block} {$website_name}</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

		<!-- Website theme -->
		<link href="{$website_path}/web/templates/frontend/default/css/styles.css" rel="stylesheet">
				
		<!-- Social buttons -->
		<link href="{$website_path}/web/templates/frontend/default/css/bootstrap-social.css" rel="stylesheet">
		
		<!-- FA icons -->
		<link href="{$website_path}/web/templates/frontend/default/css/font-awesome.css" media="all" rel="stylesheet" type="text/css" />
		
		<!-- Checkbox style -->
		<link href="{$website_path}/web/templates/frontend/default/css/bootstrap-checkbox.css" media="all" rel="stylesheet" type="text/css" />

		<!-- Favicon -->
		<link rel="shortcut icon" type="image/png" href="{$website_path}/web/templates/frontend/default/favicon.png"/>
		
		<!-- Adsense -->
		{literal}
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({
			google_ad_client: "ca-pub-2490274061878090",
			enable_page_level_ads: true
		});
		</script>
		{/literal}
		
		<!-- Tooltip -->
		{literal}
		<script>
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
            });
		</script>
		{/literal}
		
		{block name=header}{/block}
	</head>
	
	<body>
		<div id="topbar">
			<div class="container">
				<div class="col-lg-3">
					<div class="col-xs-4">
						<img class="logo" style="height: 80px; top:-15px; position: absolute;" src="{$website_path}/web/templates/frontend/default/images/logo.png" /> 
					</div>
					<div class="col-xs-8">
						<h1 style="color:orange; font-weight: bold; font-family: 'logo'; margin-top: 5px;">{$website_name}</h1>
					</div>
				</div>
				<div class="col-lg-offset-4 col-lg-5 visible-lg">
					<img src="{$website_path}/web/templates/frontend/default/images/ad1.jpg" width="468" height="60" />
				</div>
			</div>
		</div>
				
		<!-- Static navbar -->
		<nav class="navbar navbar-default navbar-static-top navbar-site" data-spy="affix" data-offset-top="100">
			<div class="container">
				<div class="navbar-header">
					<button type="button" style="background-color: white;" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">{#toggleNav#}</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand visible-xs" href="#" style="color: white; font-weight: bold;">{#menu#}</a>
				</div>
				
				<div id="navbar" class="navbar-collapse collapse">
					
					{block name=navbar}
				
					<ul class="nav navbar-nav nav-site">
						<li class="active"><a href="{$website_path}/"><span class="glyphicon glyphicon-home"></span></a></li>
						<li><a href="{$website_path}/forum">{#forums#}</a></li>
						<li><a href="{$website_path}/articles">{#articles#}</a></li>
						<li><a href="{$website_path}/contact">Contact</a></li>
						<li><a href="http://expressif.org">Official website</a></li>
					</ul>
						
					{if isset($smarty.session.idUser)}
					
					<ul class="nav navbar-nav navbar-right nav-site nav-user">
						<li class="dropdown dropdown-notifications">
							<a href="" class="dropdown-toggle" data-toggle="dropdown">
							  <i class="glyphicon glyphicon-user"></i>
							</a>
							<ul class="dropdown-menu user-menu">
								<li><a href="{$website_path}/messaging">{#myMessages#} <span class="glyphicon glyphicon-envelope pull-right"></span></a></li>
								<li class="divider"></li>
								<li><a href="{$website_path}/articles/my-articles">{#myArticles#} <span class="glyphicon glyphicon-pencil pull-right"></span></a></li>
								<li class="divider"></li>
								<li><a href="{$website_path}/members/profile/{$smarty.session.username}">{#myProfile#} <span class="glyphicon glyphicon-user pull-right"></span></a></li>
								<li class="divider"></li>
								<li><a href="{$website_path}/members/settings">{#mySettings#} <span class="glyphicon glyphicon-cog pull-right"></span></a></li>
								<li class="divider"></li>

								{if $isModerator}

								<li><a href="{$website_path}/backend">{#adminPanel#} <span class="glyphicon glyphicon-file pull-right"></span></a></li>
								<li class="divider"></li>

								{/if}

								<li><a href="{$website_path}/members/logout">{#logout#} <span class="glyphicon glyphicon-log-out pull-right"></span></a></li>
							</ul>
						</li>
					</ul>
						
					{else}	
						
					<ul class="nav navbar-nav nav-site navbar-right nav-user">
						<li><a href="{$website_path}/members/login" data-toggle="modal">{#login#}</a></li>
						<li><a href="{$website_path}/members/register" data-toggle="modal">{#register#}</a></li>
					</ul>
						
					{/if}
					
					{/block}
					
				</div><!--/.nav-collapse -->
			</div>
		</nav>
		
		{block name=breadcrumb}{/block}
		
		<div class="container-fluid">
			<div class="row">
				
				<!-- Leftside menu -->
				<nav class="col-lg-2 visible-lg">
					<div id="leftside-menu">
						
						{block name=leftsideMenu}

						<div class="row">
							<div class="panel panel-default">
								<div class="panel-heading">{#ads#}</div>
								<div class="panel-body">
									<!-- Adsense -->
									<img src="{$website_path}/web/templates/frontend/default/images/ad2.jpg" width="250" height="250" />
								</div>
							</div>
						</div>

						{/block}

					</div>
				</nav>
								
				<!-- Rightside menu -->
				<nav class="col-lg-2 col-lg-push-8 visible-lg">
					<div id="rightside-menu">
						
						{block name=rightsideMenu}

						<div class="row">
							<div class="panel panel-default">
								<div class="panel-heading">{#about#}</div>
								<div class="panel-body text-center">
									{#aboutExpressif#}
								</div>
								<div class="panel-footer text-center">	
									<a class="btn btn-social-icon btn-facebook" href="#"><span class="fa fa-facebook"></span></a>
									<a class="btn btn-social-icon btn-twitter" href="#"><span class="fa fa-twitter"></span></a>
									<a class="btn btn-social-icon btn-rss" href="#"><span class="fa fa-rss"></span></a>
								</div>
							</div>
						</div>

						{/block}
					
					</div>
				</nav>
				
				<!-- Body -->
				<section class="col-lg-8 col-lg-pull-2">
					<div id="body">

                        {if isset($module_errorMsg)}

							<div class="alert alert-warning">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<strong>{#warning#}</strong> {#$module_errorMsg#}
							</div>

                        {elseif isset($module_successMsg)}

							<div class="alert alert-success">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<strong>{#success#}</strong> {#$module_successMsg#}
							</div>

                        {/if}

						{block name=body}{/block}
					
					</div>
				</section>
			</div>
		</div>
		
		<!-- Footer -->
		<footer class="footer">
			
			{block name=footer}
			
			<div class="container-fluid">
				<p class="text-center">{#copyright#} © {'Y'|date}. {#allRightsReserved#}</p>
			</div>
		
			{/block}
		
		</footer>
	</body>
</html>