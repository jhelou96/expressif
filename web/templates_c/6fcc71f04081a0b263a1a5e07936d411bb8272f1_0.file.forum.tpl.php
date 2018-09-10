<?php
/* Smarty version 3.1.31, created on 2017-06-30 20:12:05
  from "C:\wamp64\www\expressif\app\modules\forum\views\frontend\tpl\forum.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_59569475459702_99009832',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6fcc71f04081a0b263a1a5e07936d411bb8272f1' => 
    array (
      0 => 'C:\\wamp64\\www\\expressif\\app\\modules\\forum\\views\\frontend\\tpl\\forum.tpl',
      1 => 1498498055,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59569475459702_99009832 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php
$_smarty_tpl->smarty->ext->configLoad->_loadConfigFile($_smarty_tpl, "app/modules/forum/lang/".((string)$_smarty_tpl->tpl_vars['website_lang']->value)."/frontend/forum.php", null, 0);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_29817595694754051b2_11645819', 'title');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_96625956947540c775_18243738', 'breadcrumb');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_975959569475415d14_98538612', 'body');
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "../layout.tpl");
}
/* {block 'title'} */
class Block_29817595694754051b2_11645819 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_29817595694754051b2_11645819',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'title');?>
 &bull; <?php
}
}
/* {/block 'title'} */
/* {block 'breadcrumb'} */
class Block_96625956947540c775_18243738 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'breadcrumb' => 
  array (
    0 => 'Block_96625956947540c775_18243738',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'home');?>
</a><span class="arrow"></span></li>
	<li class = "active"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'forum');?>
</li>
</ol>

<?php
}
}
/* {/block 'breadcrumb'} */
/* {block 'body'} */
class Block_975959569475415d14_98538612 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_975959569475415d14_98538612',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


<div class="row">
	<div class="col-lg-12">
		<div class="page-header"><h3><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'pageHeader');?>
</h3></div>
		<p><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'forumDesc');?>
</p>
		
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['forum_categories']->value, 'category');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['category']->value) {
?>
		
		<div class="page-header"><h3><?php echo $_smarty_tpl->tpl_vars['category']->value['name'];?>
</h3></div>
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="60%"></th>
					<th width="10%"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'subjects');?>
</th>
					<th width="10%"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'messages');?>
</th>
					<th width="20%"><?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'lastMessage');?>
</th>
				</tr>
			</thead>
			<tbody style="border-left: 6px solid orange;">
				
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['forum_subCategories']->value, 'subCategory');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['subCategory']->value) {
?>
				
				<?php if ($_smarty_tpl->tpl_vars['category']->value['id'] == $_smarty_tpl->tpl_vars['subCategory']->value['idCategory']) {?>
		
				<tr>
					<td><a href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/forum/<?php echo $_smarty_tpl->tpl_vars['subCategory']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['subCategory']->value['name'];?>
</a> <br /><h6><i><?php echo $_smarty_tpl->tpl_vars['subCategory']->value['description'];?>
</i></h6></td>
					<td><?php echo $_smarty_tpl->tpl_vars['subCategory']->value['nbTopics'];?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['subCategory']->value['nbMessages'];?>
</td>
					<td>
						<?php if (isset($_smarty_tpl->tpl_vars['subCategory']->value['lastMsgPosted'])) {?>
						
						<a href="<?php echo $_smarty_tpl->tpl_vars['website_path']->value;?>
/forum/<?php echo $_smarty_tpl->tpl_vars['subCategory']->value['topicWithMostRecentMsgURL'];?>
"><?php echo $_smarty_tpl->tpl_vars['subCategory']->value['lastMsgPosted'];?>
<br /><?php echo $_smarty_tpl->tpl_vars['subCategory']->value['topicWithMostRecentMsg'];?>
</a>
						
						<?php } else { ?>
						
						<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'noMsgFound');?>

						
						<?php }?>
					</td>
				</tr>
				
				<?php }?>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

				
			</tbody>
		</table>
		
		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

	
	</div>
</div>

<?php
}
}
/* {/block 'body'} */
}
