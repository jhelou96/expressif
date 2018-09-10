<?php
/* Smarty version 3.1.31, created on 2017-06-30 20:12:05
  from "C:\wamp64\www\expressif\web\templates\frontend\default\layout.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_59569475728264_15566632',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f6aa237179c989c197dd14bac7eb9826347fe528' => 
    array (
      0 => 'C:\\wamp64\\www\\expressif\\web\\templates\\frontend\\default\\layout.tpl',
      1 => 1498843478,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59569475728264_15566632 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
$_smarty_tpl->smarty->ext->configLoad->_loadConfigFile($_smarty_tpl, "web/templates/frontend/default/lang/".((string)$_smarty_tpl->tpl_vars['website_lang']->value)."/layout.php", null, 0);
?>


<!DOCTYPE html>
<html lang="<?php echo $_smarty_tpl->tpl_vars['website_lang']->value;?>
">
	<head>
		<title><?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1476959569475699763_92174277', 'title');
?>
 <?php echo $_smarty_tpl->tpl_vars['website_name']->value;?>
</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<?php echo '<script'; ?>
 src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"><?php echo '</script'; ?>
>

		<!-- Website theme -->
		<link href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/web/templates/frontend/default/css/styles.css" rel="stylesheet">
				
		<!-- Social buttons -->
		<link href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/web/templates/frontend/default/css/bootstrap-social.css" rel="stylesheet">
		
		<!-- FA icons -->
		<link href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/web/templates/frontend/default/css/font-awesome.css" media="all" rel="stylesheet" type="text/css" />
		
		<!-- Checkbox style -->
		<link href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/web/templates/frontend/default/css/bootstrap-checkbox.css" media="all" rel="stylesheet" type="text/css" />

		<!-- Favicon -->
		<link rel="shortcut icon" type="image/png" href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/web/templates/frontend/default/favicon.png"/>
		
		
		<?php echo '<script'; ?>
>
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
            });
		<?php echo '</script'; ?>
>
		
		
		<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11926595694756a7ce4_00012476', 'header');
?>

	</head>
	
	<body>
		<div id="topbar">
			<div class="container">
				<div class="col-lg-3">
					<div class="col-xs-4">
						<img class="logo" style="height: 80px; top:-15px; position: absolute;" src="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/web/templates/frontend/default/images/logo.png" /> 
					</div>
					<div class="col-xs-8">
						<h1 style="color:orange; font-weight: bold; font-family: 'logo'; margin-top: 5px;"><?php echo $_smarty_tpl->tpl_vars['website_name']->value;?>
</h1>
					</div>
				</div>
				<div class="col-lg-offset-4 col-lg-5 visible-lg">
					<img src="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/web/templates/frontend/default/images/ads1.gif" />
				</div>
			</div>
		</div>
				
		<!-- Static navbar -->
		<nav class="navbar navbar-default navbar-static-top navbar-site" data-spy="affix" data-offset-top="100">
			<div class="container">
				<div class="navbar-header">
					<button type="button" style="background-color: white;" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'toggleNav');?>
</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand visible-xs" href="#" style="color: white; font-weight: bold;"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'menu');?>
</a>
				</div>
				
				<div id="navbar" class="navbar-collapse collapse">
					
					<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14871595694756b4877_73998714', 'navbar');
?>

					
				</div><!--/.nav-collapse -->
			</div>
		</nav>
		
		<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_28164595694756edc37_02724193', 'breadcrumb');
?>

		
		<div class="container-fluid">
			<div class="row">
				
				<!-- Leftside menu -->
				<nav class="col-lg-2 visible-lg">
					<div id="leftside-menu">
						
						<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16365595694756f19c1_66670236', 'leftsideMenu');
?>


					</div>
				</nav>
								
				<!-- Rightside menu -->
				<nav class="col-lg-2 col-lg-push-8 visible-lg">
					<div id="rightside-menu">
						
						<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7714595694756f97c7_21459904', 'rightsideMenu');
?>

					
					</div>
				</nav>
				
				<!-- Body -->
				<section class="col-lg-8 col-lg-pull-2">
					<div id="body">

                        <?php if (isset($_smarty_tpl->tpl_vars['module_errorMsg']->value)) {?>

							<div class="alert alert-warning">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'warning');?>
</strong> <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, $_smarty_tpl->tpl_vars['module_errorMsg']->value);?>

							</div>

                        <?php } elseif (isset($_smarty_tpl->tpl_vars['module_successMsg']->value)) {?>

							<div class="alert alert-success">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'success');?>
</strong> <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, $_smarty_tpl->tpl_vars['module_successMsg']->value);?>

							</div>

                        <?php }?>

						<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2475559569475712ef1_56002595', 'body');
?>

					
					</div>
				</section>
			</div>
		</div>
		
		<!-- Footer -->
		<footer class="footer">
			
			<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2622459569475716b72_93303157', 'footer');
?>

		
		</footer>
	</body>
</html><?php }
/* {block 'title'} */
class Block_1476959569475699763_92174277 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_1476959569475699763_92174277',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'title'} */
/* {block 'header'} */
class Block_11926595694756a7ce4_00012476 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header' => 
  array (
    0 => 'Block_11926595694756a7ce4_00012476',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'header'} */
/* {block 'navbar'} */
class Block_14871595694756b4877_73998714 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'navbar' => 
  array (
    0 => 'Block_14871595694756b4877_73998714',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

				
					<ul class="nav navbar-nav nav-site">
						<li class="active"><a href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/"><span class="glyphicon glyphicon-home"></span></a></li>
						<li><a href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/forum"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'forums');?>
</a></li>
						<li><a href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/articles"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'articles');?>
</a></li>
					</ul>
						
					<?php if (isset($_SESSION['idUser'])) {?>
					
					<ul class="nav navbar-nav navbar-right nav-site nav-user">
						<li class="dropdown dropdown-notifications">
							<a href="" class="dropdown-toggle" data-toggle="dropdown">
							  <i class="glyphicon glyphicon-user"></i>
							</a>
							<ul class="dropdown-menu user-menu">
								<li><a href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/messaging"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'myMessages');?>
 <span class="glyphicon glyphicon-envelope pull-right"></span></a></li>
								<li class="divider"></li>
								<li><a href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/articles/my-articles"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'myArticles');?>
 <span class="glyphicon glyphicon-pencil pull-right"></span></a></li>
								<li class="divider"></li>
								<li><a href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/members/profile/<?php echo $_SESSION['username'];?>
"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'myProfile');?>
 <span class="glyphicon glyphicon-user pull-right"></span></a></li>
								<li class="divider"></li>
								<li><a href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/members/settings"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'mySettings');?>
 <span class="glyphicon glyphicon-cog pull-right"></span></a></li>
								<li class="divider"></li>

								<?php if ($_smarty_tpl->tpl_vars['isModerator']->value) {?>

								<li><a href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/backend"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'adminPanel');?>
 <span class="glyphicon glyphicon-file pull-right"></span></a></li>
								<li class="divider"></li>

								<?php }?>

								<li><a href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/members/logout"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'logout');?>
 <span class="glyphicon glyphicon-log-out pull-right"></span></a></li>
							</ul>
						</li>
					</ul>
						
					<?php } else { ?>	
						
					<ul class="nav navbar-nav nav-site navbar-right nav-user">
						<li><a href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/members/login" data-toggle="modal"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'login');?>
</a></li>
						<li><a href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/members/register" data-toggle="modal"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'register');?>
</a></li>
					</ul>
						
					<?php }?>
					
					<?php
}
}
/* {/block 'navbar'} */
/* {block 'breadcrumb'} */
class Block_28164595694756edc37_02724193 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'breadcrumb' => 
  array (
    0 => 'Block_28164595694756edc37_02724193',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'breadcrumb'} */
/* {block 'leftsideMenu'} */
class Block_16365595694756f19c1_66670236 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'leftsideMenu' => 
  array (
    0 => 'Block_16365595694756f19c1_66670236',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


						<div class="row">
							<div class="panel panel-default">
								<div class="panel-heading"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'ads');?>
</div>
								<div class="panel-body">
									<center><img class="img-responsive" src="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/web/templates/frontend/default/images/ads2.gif" /></center>
								</div>
							</div>
						</div>

						<?php
}
}
/* {/block 'leftsideMenu'} */
/* {block 'rightsideMenu'} */
class Block_7714595694756f97c7_21459904 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'rightsideMenu' => 
  array (
    0 => 'Block_7714595694756f97c7_21459904',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


						<div class="row">
							<div class="panel panel-default">
								<div class="panel-heading"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'about');?>
</div>
								<div class="panel-body text-center">
									<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'aboutExpressif');?>

								</div>
								<div class="panel-footer text-center">	
									<a class="btn btn-social-icon btn-facebook" href="#"><span class="fa fa-facebook"></span></a>
									<a class="btn btn-social-icon btn-twitter" href="#"><span class="fa fa-twitter"></span></a>
									<a class="btn btn-social-icon btn-rss" href="#"><span class="fa fa-rss"></span></a>
								</div>
							</div>
						</div>

						<?php
}
}
/* {/block 'rightsideMenu'} */
/* {block 'body'} */
class Block_2475559569475712ef1_56002595 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_2475559569475712ef1_56002595',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'body'} */
/* {block 'footer'} */
class Block_2622459569475716b72_93303157 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'footer' => 
  array (
    0 => 'Block_2622459569475716b72_93303157',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

			
			<div class="container-fluid">
				<p class="text-center"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'copyright');?>
 © <?php echo date('Y');?>
. <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'allRightsReserved');?>
</p>
			</div>
		
			<?php
}
}
/* {/block 'footer'} */
}
