<?php
/* Smarty version 3.1.31, created on 2017-07-01 15:07:17
  from "C:\wamp64\www\expressif\app\modules\members\views\frontend\layout.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_59579e85d97f40_87477381',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fa9a713e0d80755b2d2507aa60c595d3370ab36e' => 
    array (
      0 => 'C:\\wamp64\\www\\expressif\\app\\modules\\members\\views\\frontend\\layout.tpl',
      1 => 1497522059,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59579e85d97f40_87477381 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php
$_smarty_tpl->smarty->ext->configLoad->_loadConfigFile($_smarty_tpl, "app/modules/members/lang/".((string)$_smarty_tpl->tpl_vars['website_lang']->value)."/alerts.php", null, 0);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2705959579e85d904c4_98173954', 'header');
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "web/templates/frontend/".((string)$_smarty_tpl->tpl_vars['website_frontendTemplate']->value)."/layout.tpl");
}
/* {block 'module_header'} */
class Block_1521659579e85d93d55_72137552 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'module_header'} */
/* {block 'header'} */
class Block_2705959579e85d904c4_98173954 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header' => 
  array (
    0 => 'Block_2705959579e85d904c4_98173954',
  ),
  'module_header' => 
  array (
    0 => 'Block_1521659579e85d93d55_72137552',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<!-- Module related CSS files -->
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/app/modules/members/views/frontend/css/members.css" />

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1521659579e85d93d55_72137552', 'module_header', $this->tplIndex);
?>


<?php
}
}
/* {/block 'header'} */
}
