<?php
/* Smarty version 3.1.31, created on 2017-07-01 15:07:17
  from "C:\wamp64\www\expressif\app\modules\members\views\frontend\tpl\register.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_59579e85c36ef7_91136806',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '89c69af40c20962818670aa67e9d7c9c891f2139' => 
    array (
      0 => 'C:\\wamp64\\www\\expressif\\app\\modules\\members\\views\\frontend\\tpl\\register.tpl',
      1 => 1498846284,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59579e85c36ef7_91136806 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php
$_smarty_tpl->smarty->ext->configLoad->_loadConfigFile($_smarty_tpl, "app/modules/members/lang/".((string)$_smarty_tpl->tpl_vars['website_lang']->value)."/frontend/register.php", null, 0);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1912159579e85bbef09_09660786', 'title');
?>
		
<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2754259579e85bca670_62993250', 'rightsideMenu');
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1307959579e85bce0c3_62208325', 'leftsideMenu');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2944459579e85bd1938_78456858', 'breadcrumb');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2481359579e85bdd275_66632452', 'body');
?>
	<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, "../layout.tpl");
}
/* {block 'title'} */
class Block_1912159579e85bbef09_09660786 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_1912159579e85bbef09_09660786',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'register');?>
 &bull; <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'members');?>
 &bull; <?php
}
}
/* {/block 'title'} */
/* {block 'rightsideMenu'} */
class Block_2754259579e85bca670_62993250 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'rightsideMenu' => 
  array (
    0 => 'Block_2754259579e85bca670_62993250',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'rightsideMenu'} */
/* {block 'leftsideMenu'} */
class Block_1307959579e85bce0c3_62208325 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'leftsideMenu' => 
  array (
    0 => 'Block_1307959579e85bce0c3_62208325',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'leftsideMenu'} */
/* {block 'breadcrumb'} */
class Block_2944459579e85bd1938_78456858 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'breadcrumb' => 
  array (
    0 => 'Block_2944459579e85bd1938_78456858',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'home');?>
</a><span class="arrow"></span></li>
	<li><a href = "#"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'members');?>
</a><span class="arrow"></span></li>
	<li class = "active"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'register');?>
</li>
</ol>

<?php
}
}
/* {/block 'breadcrumb'} */
/* {block 'body'} */
class Block_2481359579e85bdd275_66632452 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_2481359579e85bdd275_66632452',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


<div class="row">
	<div class="alert alert-info">
		<strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'alreadyRegistered');?>
</strong> <a href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/members/login" class="link"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'linkToConnect');?>
</a>.
	</div>
</div>
<div class="row">
	<div class="col-lg-6">
		<div class="page-header"><h3><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'registerToday');?>
</h3></div>
		<form action="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/members/register" method="post">
			<div class="form-group">

				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
					<input type="text" class="form-control" name="register_username" <?php if (isset($_smarty_tpl->tpl_vars['register_username']->value)) {?> value="<?php echo $_smarty_tpl->tpl_vars['register_username']->value;?>
" <?php } else { ?> placeholder="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'username');?>
" <?php }?>>
				</div>

				<br />

				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
					<input type="email" class="form-control" name="register_email" <?php if (isset($_smarty_tpl->tpl_vars['register_email']->value)) {?> value="<?php echo $_smarty_tpl->tpl_vars['register_email']->value;?>
" <?php } else { ?> placeholder="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'email');?>
" <?php }?>>
				</div>

				<br />

				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
					<input type="password" class="form-control" name="register_password" placeholder="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'password');?>
">
				</div>

				<div class="checkbox checkbox-primary">
					<input type="checkbox" name="register_rules" checked>
					<label for="register_rules">
                        <?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'IAgreeTo');?>
 <a href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/pages/rules" class="link"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'rules');?>
</a>
					</label>

					<span class="pull-right">
						<a href="#" class="link"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'activationMailNotRecieved');?>
</a>
					</span>
				</div>
			</div>
			<input type="submit" class="btn btn-primary btn-md btn-block" value="Register" />
		</form>
	</div>
	<div class="col-lg-6">
		<div class="page-header"><h3><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'reasonsToRegister');?>
</h3></div>
		<ul class="registrationList">
			<li><strong><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'timeToRegister');?>
</strong></li>
			<li><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'participateOnForums');?>
</li>
			<li><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'commentArticles');?>
</li>
			<li><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'writeArticles');?>
</li>
			<li><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'andMore');?>
</li>
		</ul>
	</div>
</div>

<?php
}
}
/* {/block 'body'} */
}
