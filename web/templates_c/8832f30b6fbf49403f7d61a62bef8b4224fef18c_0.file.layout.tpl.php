<?php
/* Smarty version 3.1.31, created on 2017-06-30 20:12:05
  from "C:\wamp64\www\expressif\app\modules\forum\views\frontend\layout.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_595694755ba701_61793228',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8832f30b6fbf49403f7d61a62bef8b4224fef18c' => 
    array (
      0 => 'C:\\wamp64\\www\\expressif\\app\\modules\\forum\\views\\frontend\\layout.tpl',
      1 => 1497808749,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_595694755ba701_61793228 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php
$_smarty_tpl->smarty->ext->configLoad->_loadConfigFile($_smarty_tpl, "app/modules/forum/lang/".((string)$_smarty_tpl->tpl_vars['website_lang']->value)."/alerts.php", null, 0);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_24373595694755a7b78_84914130', 'header');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1339595694755b5a75_76027090', 'leftsideMenu');
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "web/templates/frontend/".((string)$_smarty_tpl->tpl_vars['website_frontendTemplate']->value)."/layout.tpl");
}
/* {block 'module_header'} */
class Block_24522595694755afb03_88372933 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'module_header'} */
/* {block 'header'} */
class Block_24373595694755a7b78_84914130 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header' => 
  array (
    0 => 'Block_24373595694755a7b78_84914130',
  ),
  'module_header' => 
  array (
    0 => 'Block_24522595694755afb03_88372933',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<!-- Module related CSS files -->
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/app/modules/forum/views/frontend/css/forum.css" />

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
<?php echo '<script'; ?>
>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
<?php echo '</script'; ?>
>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_24522595694755afb03_88372933', 'module_header', $this->tplIndex);
?>


<?php
}
}
/* {/block 'header'} */
/* {block 'leftsideMenu'} */
class Block_1339595694755b5a75_76027090 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'leftsideMenu' => 
  array (
    0 => 'Block_1339595694755b5a75_76027090',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">Search</div>
        <div class="panel-body">
            <form action="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/forum/search" method="post" name="search">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" name="forum_search" placeholder="Search">
                        <div class="input-group-addon"><a href="#" onclick="document.forms['search'].submit();"><span class="glyphicon glyphicon-search"></span></a></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
}
}
/* {/block 'leftsideMenu'} */
}
