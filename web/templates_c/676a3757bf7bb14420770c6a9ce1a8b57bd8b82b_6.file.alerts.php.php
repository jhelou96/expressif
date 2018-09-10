<?php /* Smarty version 3.1.31, created on 2017-06-30 20:12:05
         compiled from "C:\wamp64\www\expressif\app\modules\forum\lang\en\alerts.php" */ ?>
<?php
/* Smarty version 3.1.31, created on 2017-06-30 20:12:05
  from "C:\wamp64\www\expressif\app\modules\forum\lang\en\alerts.php" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_59569475665dc9_14178909',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '676a3757bf7bb14420770c6a9ce1a8b57bd8b82b' => 
    array (
      0 => 'C:\\wamp64\\www\\expressif\\app\\modules\\forum\\lang\\en\\alerts.php',
      1 => 1498659356,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59569475665dc9_14178909 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->smarty->ext->configLoad->_loadConfigVars($_smarty_tpl, array (
  'sections' => 
  array (
  ),
  'vars' => 
  array (
    'warning' => 'Warning!',
    'noMsgFound' => 'No message',
    'categoryAlreadyExists' => 'A category with the same name already exists.',
    'subCategoryAlreadyExists' => 'A sub-category with the same name already exists.',
    'categoryDoesNotExist' => 'Category does not exist.',
    'subCategoryDoesNotExist' => 'Sub-category does not exist.',
    'errorTopicAlreadyExists' => 'A topic with the same title already exists.',
    'noTopicsFound' => 'No topics in this category',
    'categoriesMustBeCreatedFirst' => 'You need to create a category before you can create a sub-category.',
    'errorTopicLocked' => 'The topic is locked. You cannot participate to it anymore.',
    'errorConnectionNeededToPostMsg' => 'You have to be <a href="{$website_path}/membres/connexion">connected</a> if you want to post a message.',
    'searchInputCannotBeEmpty' => 'Search input cannot be empty.',
    'searchInputTooShort' => 'Search input should contain at least 3 characters.',
    'category_idShouldBeNumeric' => 'Category ID should be numeric.',
    'category_idCannotBeEmpty' => 'Category ID cannot be empty.',
    'category_nameShouldBeString' => 'Category name should be string.',
    'category_nameCannotBeEmpty' => 'Category name cannot be empty.',
    'like_idShouldBeNumeric' => 'Like ID should be numeric',
    'like_idCannotBeEmpty' => 'Like ID cannot be empty.',
    'like_idUserShouldBeNumeric' => 'User ID should be numeric.',
    'like_idUserCannotBeEmpty' => 'User ID cannot be empty.',
    'like_idMessageShouldBeNumeric' => 'Message ID should be numeric.',
    'like_idMessageCannotBeEmpty' => 'Message ID cannot be empty.',
    'like_likedShouldBeNumeric' => 'Liked status should be numeric.',
    'like_dislikedShouldBeNumeric' => 'Disliked status should be numeric.',
    'message_idShouldBeNumeric' => 'Message ID should be numeric.',
    'message_idCannotBeEmpty' => 'Message ID cannot be empty.',
    'message_idAuthorShouldBeNumeric' => 'Author ID should be numeric.',
    'message_idAuthorCannotBeEmpty' => 'Author ID cannot be empty.',
    'message_idTopicShouldBeNumeric' => 'Topic ID should be numeric.',
    'message_idTopicCannotBeEmpty' => 'Topic ID cannot be empty.',
    'message_messageShouldBeString' => 'Message should be string.',
    'message_messageCannotBeEmpty' => 'Message cannot be empty.',
    'message_helpedAuthorShouldBeNumeric' => 'Message helped status should be numeric.',
    'message_publicationDateCannotBeEmpty' => 'Message publication date cannot be empty.',
    'idEditorShouldBeNumeric' => 'ID of the user who edited this message should be numeric.',
    'message_msgInTopicShouldBeNumeric' => 'Position of the message in the topic should be numeric.',
    'message_msgInTopicCannotBeEmpty' => 'Position of the message in the topic cannot be empty.',
    'subCategory_idShouldBeNumeric' => 'Subcategory ID should be numeric.',
    'subCategory_idCannotBeEmpty' => 'Subcategory ID cannot be empty.',
    'subCategory_idCategoryShouldBeNumeric' => 'Category ID should be numeric.',
    'subCategory_idCategoryCannotBeEmpty' => 'Category ID cannot be empty.',
    'subCategory_nameShouldBeString' => 'Subcategory name should be string.',
    'subCategory_nameCannotBeEmpty' => 'Subcategory name cannot be empty.',
    'subCategory_nameContainsUnauthorizedChars' => 'Subcategory name contains unauthorized characters.',
    'subCategory_descriptionShouldBeString' => 'Subcategory description should be string.',
    'subCategory_descriptionCannotBeEmpty' => 'Subcategory description cannot be empty.',
    'topic_idShouldBeNumeric' => 'Topic ID should be string.',
    'topic_idCannotBeEmpty' => 'Topic ID cannot be empty.',
    'topic_idSubCategoryShouldBeNumeric' => 'Subcategory ID should be numeric.',
    'topic_idSubCategoryCannotBeEmpty' => 'Subcategory ID cannot be empty.',
    'topic_idAuthorShouldBeNumeric' => 'Author ID should be numeric.',
    'topic_idAuthorCannotBeEmpty' => 'Author ID cannot be empty.',
    'topic_titleShouldBeString' => 'Topic title should be string.',
    'topic_titleCannotBeEmpty' => 'Topic title cannot be empty.',
    'topic_titleContainsUnauthorizedChars' => 'Topic title contains unauthorized characters.',
    'topic_postitShouldBeNumeric' => 'Post-it status should be numeric.',
    'topic_solvedShouldBeNumeric' => 'Solved status should be numeric.',
    'topic_lockedShouldBeNumeric' => 'Locked status should be numeric.',
    'topic_creationDateCannotBeEmpty' => 'Created date cannot be empty.',
    'success' => 'Success!',
    'successTopicSolved' => 'The author of the thread has found a solution to his problem.',
    'msgHasBeenDeleted' => 'The message has been deleted.',
    'topicCreated' => 'Your topic has been created',
    'topicLocked' => 'The topic has been correctly locked.',
    'topicReopened' => 'The topic has been correctly reopened.',
    'topicRemoved' => 'The topic has been correctly removed.',
    'categoryHasBeenEdited' => 'Category has been edited.',
    'categoryHasBeenCreated' => 'Category has been created.',
    'categoryHasBeenRemoved' => 'Category has been removed.',
    'subCategoryHasBeenCreated' => 'Sub-category has been created.',
    'subCategoryHasBeenEdited' => 'Sub-category has been edited.',
    'subCategoryHasBeenRemoved' => 'Sub-category has been removed.',
  ),
));
}
}
