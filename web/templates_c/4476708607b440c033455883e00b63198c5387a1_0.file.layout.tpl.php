<?php
/* Smarty version 3.1.31, created on 2017-07-01 15:04:37
  from "C:\wamp64\www\expressif\app\modules\pages\views\frontend\layout.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_59579de507de26_08455328',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4476708607b440c033455883e00b63198c5387a1' => 
    array (
      0 => 'C:\\wamp64\\www\\expressif\\app\\modules\\pages\\views\\frontend\\layout.tpl',
      1 => 1497886912,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59579de507de26_08455328 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php
$_smarty_tpl->smarty->ext->configLoad->_loadConfigFile($_smarty_tpl, "app/modules/pages/lang/".((string)$_smarty_tpl->tpl_vars['website_lang']->value)."/alerts.php", null, 0);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2034659579de5072928_02804657', 'header');
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "web/templates/frontend/".((string)$_smarty_tpl->tpl_vars['website_frontendTemplate']->value)."/layout.tpl");
}
/* {block 'module_header'} */
class Block_1309959579de50795f1_22518394 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'module_header'} */
/* {block 'header'} */
class Block_2034659579de5072928_02804657 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header' => 
  array (
    0 => 'Block_2034659579de5072928_02804657',
  ),
  'module_header' => 
  array (
    0 => 'Block_1309959579de50795f1_22518394',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<!-- BBCode editor -->
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/vendor/editor/bbcode.css" />
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/vendor/editor/bbcode.js"><?php echo '</script'; ?>
>

<!-- Syntax highlighting -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/highlight.js/9.5.0/styles/default.min.css">
<?php echo '<script'; ?>
 src="//cdn.jsdelivr.net/highlight.js/9.5.0/highlight.min.js"><?php echo '</script'; ?>
>


<?php echo '<script'; ?>
>hljs.initHighlightingOnLoad();<?php echo '</script'; ?>
>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1309959579de50795f1_22518394', 'module_header', $this->tplIndex);
?>


<?php
}
}
/* {/block 'header'} */
}
