<?php
/* Smarty version 3.1.31, created on 2017-07-01 15:04:36
  from "C:\wamp64\www\expressif\app\modules\pages\views\frontend\tpl\page.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_59579de4ddc749_94121275',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '89aa1aa458353b41f1f04a20b2bae920bb5e9b5d' => 
    array (
      0 => 'C:\\wamp64\\www\\expressif\\app\\modules\\pages\\views\\frontend\\tpl\\page.tpl',
      1 => 1498498176,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59579de4ddc749_94121275 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php
$_smarty_tpl->smarty->ext->configLoad->_loadConfigFile($_smarty_tpl, "app/modules/pages/lang/".((string)$_smarty_tpl->tpl_vars['website_lang']->value)."/frontend/page.php", null, 0);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3134259579de4d6b801_31246106', 'title');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2177559579de4d92a39_56609630', 'breadcrumb');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3113259579de4dd6de7_86622649', 'body');
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "../layout.tpl");
}
/* {block 'title'} */
class Block_3134259579de4d6b801_31246106 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_3134259579de4d6b801_31246106',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
echo $_smarty_tpl->tpl_vars['page_name']->value;?>
 &bull; <?php
}
}
/* {/block 'title'} */
/* {block 'breadcrumb'} */
class Block_2177559579de4d92a39_56609630 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'breadcrumb' => 
  array (
    0 => 'Block_2177559579de4d92a39_56609630',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<?php if (!$_smarty_tpl->tpl_vars['page_isHomepage']->value) {?>

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'home');?>
</a><span class="arrow"></span></li>
	<li class = "active"><?php echo $_smarty_tpl->tpl_vars['page_name']->value;?>
</li>
</ol>

<?php }
}
}
/* {/block 'breadcrumb'} */
/* {block 'body'} */
class Block_3113259579de4dd6de7_86622649 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_3113259579de4dd6de7_86622649',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


<div class="row">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading"><?php echo $_smarty_tpl->tpl_vars['page_name']->value;?>
</div>
					<div class="panel-body"><?php echo $_smarty_tpl->tpl_vars['page_content']->value;?>
</div>
				</div>
			</div>
		</div>	
	</div>
</div>

<?php
}
}
/* {/block 'body'} */
}
