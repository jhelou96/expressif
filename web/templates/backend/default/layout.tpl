{config_load file="web/templates/backend/default/lang/{$website_lang}.php"}
{config_load file="app/backend/lang/{$website_lang}/alerts.php"}

<!DOCTYPE html>
<html lang="fr">
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
		<link href="{$website_path}/web/templates/backend/default/css/styles.css" rel="stylesheet">

		<!-- FA icons -->
		<link href="{$website_path}/web/templates/backend/default/css/font-awesome.css" media="all" rel="stylesheet" type="text/css" />

		<!-- Submenu dropdown -->
		<script src="{$website_path}/web/templates/backend/default/js/submenu.js"></script>

		<!-- Checkbox style -->
		<link href="{$website_path}/web/templates/backend/default/css/bootstrap-checkbox.css" media="all" rel="stylesheet" type="text/css" />

		<!-- Favicon -->
		<link rel="shortcut icon" type="image/png" href="{$website_path}/web/templates/backend/default/favicon.png"/>
		
		
		{block name = header}{/block}
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
			</div>
		</div>
		
		<!-- Body -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-2" style="padding-top:20px;">
					<div class="panel panel-default">
						<div class="sidebar" style="display: block;">
							<ul class="nav">
								<!-- Main menu -->
								<li class="current"><a href="{$website_path}/backend"><i class="glyphicon glyphicon-home"></i> {#dashboard#}</a></li>
								<li><a href="{$website_path}/backend/configuration"><i class="glyphicon glyphicon-cog"></i> {#configuration#}</a></li>
								<li class="submenu">
									<a href="#"><i class="glyphicon glyphicon-file"></i> {#plugins#} <span class="caret pull-right"></span></a>
									<!-- Sub menu -->
									<ul>
										<li><a href="{$website_path}/backend/modules">{#managePlugins#}</a></li>
										<li><a href="{$website_path}/backend/modules/install">{#installPlugins#}</a></li>
									</ul>
								</li>
								<li><a href="{$website_path}/backend/templates"><i class="glyphicon glyphicon-picture"></i> {#templates#}</a></li>
								<li><a href="#"><i class="glyphicon glyphicon-book"></i> {#documentation#}</a></li>
								<li><a href="#"><i class="glyphicon glyphicon-question-sign"></i> {#support#}</a></li>
								<li><a href="{$website_path}/"><i class="glyphicon glyphicon-log-out"></i> {#returnToWebsite#}</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-lg-10">
					<div id="body">

                        {if isset($module_errorMsg)}

							<br />
							<div class="alert alert-warning">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<strong>{#warning#}</strong> {#$module_errorMsg#}
							</div>

                        {elseif isset($module_successMsg)}

							<br />
							<div class="alert alert-success">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<strong>{#success#}</strong> {#$module_successMsg#}
							</div>

                        {/if}

						{block name = body}{/block}

					</div>
				</div>
			</div>
		</div>
		
		<!-- Footer -->
		<footer class="footer">
			
			<div class="container-fluid">
				<p class="text-center">{#copyright#} © {'Y'|date}. {#allRightsReserved#}</p>
			</div>
		
		</footer>
	</body>
</html>