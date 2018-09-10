{extends file="../layout.tpl"}
{config_load file="app/modules/forum/lang/{$website_lang}/frontend/showTopic.php"}=

{block name=title}{$forum_topicTitle} &bull; {#title#} &bull; {/block}

{block name=breadcrumb}

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
	<li><a href = "{$website_path}/forum">{#forum#}</a><span class="arrow"></span></li>
	<li><a href = "{$website_path}/forum/{$forum_subCategoryURL}">{$forum_subCategoryName}</a><span class="arrow"></span></li>
	<li class = "active">{$forum_topicTitle}</li>
</ol>

{/block}

{block name=body}

<div class="row">
	<div class="col-lg-12">
		<div class="page-header"><h3>{if $forum_topicSolved}[{#solved#}]{/if} {$forum_topicTitle}</h3></div>
		
		{if $forum_topicSolved}
		
		<div class="alert alert-success">
			<strong>{#success#}</strong> {#successTopicSolved#}
		</div>
		
		{/if}
		
		{foreach from = $forum_messages item = message}
		
		<div id="m{$message.msgInTopic}" style="display:block;padding-top:130px; margin-top:-130px;"></div>
		<div class="row">
			<div class="col-lg-1"> 
				<a href="#"><img style="padding-bottom: 5px; height: 75px; width: 75px;" src="{$website_path}/{$message.authorAvatar}" /></a>
				
				{if $message.authorLevel eq 2} 
				
				<span class="label label-success">{#moderator#}</span> 
				
				{elseif $message.authorLevel eq 3}
				
				<span class="label label-danger">{#administrator#}</span> 
				
				{/if}
				
			</div>
			<div class="col-lg-11">
				
				{if $message.helpedAuthor}
			
				<div class="forum-box-success">
				
				{else}
				
				<div class="forum-box-default">
				
				{/if}
				
					<div class="box-header">
						<div class="row">
							<div class="col-lg-6" style="padding-left: 0px;">
								<span class="pull-left">
									{if $message.msgWrittenByAuthor} <span class="label label-primary">Auteur</span> &nbsp; {/if} {if $message.authorIsConnected}<img src="{$website_path}/app/modules/members/views/frontend/images/online.png" title="{#online#}" style="width: 12px; height: 12px;" />{/if} <a href="{$website_path}/members/profile/{$message.authorUsername}">{$message.authorUsername}</a> &nbsp; <span class="date"><a href="#">{$message.publicationDate}</a></span>
								</span>
							</div>
							<div class="col-lg-6" style="padding-right: 0px;">
								<span class="pull-right">
									<a href="#"><i style="color:#8A8B8A;" class="glyphicon glyphicon-exclamation-sign"></i> {#report#}</a>{if $message.isAuthor OR $isModerator} &nbsp; <a href="{$website_path}/forum/{$forum_subCategoryURL}/{$forum_topicURL}/action/edit-message/{$message.id}"><i style="color:#8A8B8A;" class="glyphicon glyphicon-edit"></i> {#edit#}</a> {/if} {if $isModerator} &nbsp; <a href="{$website_path}/forum/{$forum_subCategoryURL}/{$forum_topicURL}/action/delete-message/{$message.id}" onclick="return(confirm('Etes-vous sûr de vouloir supprimer ce message ?'));"><i style="color:#8A8B8A;" class="glyphicon glyphicon-trash"></i> {#delete#}</a>{/if} {if !$message.helpedAuthor AND $isAuthor AND !$message.isAuthor} <a href="{$website_path}/forum/{$forum_subCategoryURL}/{$forum_topicURL}/action/message-helped/{$message.id}"><i style="color:#8A8B8A;" class="glyphicon glyphicon-ok"></i> {#msgHelpedMe#}</a> {/if}
								</span>
							</div>
						</div>
						<br />
					
						{if $message.helpedAuthor eq 1}
						
						<p style="color: green;"><i class="glyphicon glyphicon-ok"></i> {#answerHelpedAuthor#}</p>
						
						{/if}
						
					</div>
					<div class="box-body">
						<p>{$message.message}</p>
						<br />
						<div class="row">
							<div class="col-lg-8">
								
								{if isset($message.lastEditionDate) AND isset($message.editorUsername)}
								
								<span class="pull-left" style="padding-top: 7px; color: #8A8B8A"><i class="glyphicon glyphicon-pencil"></i> <i>{#editedBy#} <a href="#">{$message.editorUsername}</a> {$message.lastEditionDate}</i></span>
							
								{/if}
							
							</div>
							<div class="col-lg-4" style="padding-right: 0px;">
								<span class="pull-right">
								
									{if $isConnected}
								
									{if $message.userAlreadyVoted eq liked}
									
									<a href="{$website_path}/forum/{$forum_subCategoryURL}/{$forum_topicURL}/action/like-message/{$message.id}" class="btn btn-success stat-item active"><i class="fa fa-thumbs-up icon"></i> {$message.likes}</a> &nbsp; 
									<a href="{$website_path}/forum/{$forum_subCategoryURL}/{$forum_topicURL}/action/dislike-message/{$message.id}" class="btn btn-danger stat-item"><i class="fa fa-thumbs-down icon"></i> {$message.dislikes}</a>
									
									{elseif $message.userAlreadyVoted eq disliked}
									
									<a href="{$website_path}/forum/{$forum_subCategoryURL}/{$forum_topicURL}/action/like-message/{$message.id}" class="btn btn-success stat-item"><i class="fa fa-thumbs-up icon"></i> {$message.likes}</a> &nbsp; 
									<a href="{$website_path}/forum/{$forum_subCategoryURL}/{$forum_topicURL}/action/dislike-message/{$message.id}" class="btn btn-danger stat-item active"><i class="fa fa-thumbs-down icon"></i> {$message.dislikes}</a>
									
									{else}
									
									<a href="{$website_path}/forum/{$forum_subCategoryURL}/{$forum_topicURL}/action/like-message/{$message.id}" class="btn btn-success stat-item"><i class="fa fa-thumbs-up icon"></i> {$message.likes}</a> &nbsp; 
									<a href="{$website_path}/forum/{$forum_subCategoryURL}/{$forum_topicURL}/action/dislike-message/{$message.id}" class="btn btn-danger stat-item"><i class="fa fa-thumbs-down icon"></i> {$message.dislikes}</a>
									
									{/if}
									
									{else}
									
									<a href="#" class="btn btn-success stat-item disabled"><i class="fa fa-thumbs-up icon"></i> {$message.likes}</a> &nbsp; 
									<a href="#" class="btn btn-danger stat-item disabled"><i class="fa fa-thumbs-down icon"></i> {$message.dislikes}</a>
									
									{/if}
									
								</span>
								
							</div>
						</div>
					</div>
					
					{if isset($message.authorSignature)}
					
					<div class="box-footer">
						<p>{$message.authorSignature}</p>
					</div>
					
					{/if}
					
				</div>
			</div>
		</div>
		<br />
		
		{/foreach}
		
		{if isset($forum_previewMsg)}
		
		<br />
		<div id="mpreview" style="display:block;padding-top:130px; margin-top:-130px;"></div>
		<div class="row">
			<div class="col-lg-1">
				<p>{#preview#} <i>{#msgNotPublishedYet#}</i></p>
			</div>
			<div class="col-lg-11">
				<strong>{#warningHowToSendMsg#}.</strong>
				<div class="forum-box-default">
					<div class="box-body">
						<p>{$forum_previewMsg}</p>
					</div>
				</div>
			</div>
		</div>
		<br />
		
		{/if}
		
		<p class="text-center">
			
			{if $isModerator OR $isAuthor}
			
			{if $forum_topicSolved}
			
			<a href="{$website_path}/forum/{$forum_subCategoryURL}/{$forum_topicURL}/action/topic-not-solved" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-ok"></span> {#notSolved#}</a>
			
			{else}
			
			<a href="{$website_path}/forum/{$forum_subCategoryURL}/{$forum_topicURL}/action/topic-solved" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-ok"></span> {#solved#}</a>
			
			{/if}
			
			{/if}
			
			{if $isModerator}
			
			{if $forum_topicPostit}
			
			<a href="{$website_path}/forum/{$forum_subCategoryURL}/{$forum_topicURL}/action/detach-topic" class="btn btn-sm btn-info"><span class="glyphicon glyphicon-pushpin"></span> {#detach#}</a>
			
			{else}
			
			<a href="{$website_path}/forum/{$forum_subCategoryURL}/{$forum_topicURL}/action/pin-topic" class="btn btn-sm btn-info"><span class="glyphicon glyphicon-pushpin"></span> {#postit#}</a>
			
			{/if}
			
			{if $forum_topicLocked}
			
			<a href="{$website_path}/forum/{$forum_subCategoryURL}/{$forum_topicURL}/action/unlock-topic" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-ban-circle"></span> {#unlock#}</a>
			
			{else}
			
			<a href="{$website_path}/forum/{$forum_subCategoryURL}/{$forum_topicURL}/action/lock-topic" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-ban-circle"></span> {#lock#}</a>
			
			{/if}
			
			<a href="{$website_path}/forum/{$forum_subCategoryURL}/{$forum_topicURL}/action/delete-topic" class="btn btn-sm btn-danger" onclick="return(confirm('{#deleteConfirmation#}'));"><span class="glyphicon glyphicon-trash"></span> {#delete#}</a>
			
			{/if}
		</p>
		
		{$pagination}
		
		{if $isConnected}
		
		{if $forum_topicLocked}
		
		<div class="alert alert-warning">
			<strong>{#warning#}</strong>  {#errorTopicLocked#}
		</div>
		
		{else}
		
		{if $isLastPage}
		
		<form class="form-horizontal" action="{$website_path}/forum/{$forum_postURL}#mpreview" method="post" name="editor">
			<div class="form-group">
				<div class="col-lg-12">
					<div class="editor">
						<h3>{#editor#}</h3>
						
						{$bbcodeEditor_buttons}
						
						<textarea name="editor_textarea" placeholder="{#msgContent#}" style="width: 100%;" rows="20">{if isset($forum_textAreaDefault)} {$forum_textAreaDefault} {/if}</textarea>
						
					</div>
				</div>
			</div>
			
			<div class="form-group"> 
				<div class="col-lg-12">
					<span class="pull-right"><button type="submit" class="btn btn-default" name="preview">{#preview#}</button> &nbsp; <button type="submit" class="btn btn-primary" name="post">{#send#}</button></span>
				</div>
			</div>
		</form>
		
		{/if}
		
		{/if}
		
		{else}
		
		<div class="alert alert-info">
			<strong>{#warning#}</strong> {#errorConnectionNeededToPostMsg#}
		</div>
		
		{/if}
		
	</div>
</div>

{literal}
<script type="text/javascript">
// <![CDATA[
var form_name = 'editor';
var text_name = 'editor_textarea';
// ]]>
</script>
{/literal}

{/block}