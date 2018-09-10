{extends file="../layout.tpl"}
{config_load file="app/modules/messaging/lang/{$website_lang}/frontend/readThread.php"}

{block name=title}{#readThread#} &bull; {/block}		

{block name=leftsideMenu}

<div class="row">
	<div class="panel panel-default">
		<div class="panel-heading">{#threadInfos#}</div>
		<ul class="list-group">
			<li class="list-group-item">
				<span class="badge">{$messaging_nbParticipants}</span>
				{#nbParticipants#}
			</li>
			<li class="list-group-item">
				<span class="badge">{$messaging_threadCreationDate}</span>
				{#threadCreationDate#}
			</li>
			<li class="list-group-item">
				<span class="badge">{$messaging_nbMessages}</span>
				{#nbMessages#}
			</li>
			<li class="list-group-item">
				{#participants#}: {foreach from = $messaging_listParticipants item = $participant name = participant} <a href="{$website_path}/members/profile/{$participant}">{$participant}</a>{(!$smarty.foreach.participant.last) ? ',':''} {/foreach}
			</li>
		</ul>
		<div class="panel-footer">
			<p class="text-center"><a href="{$website_path}/messaging/thread/{$messaging_threadID}/delete" class="btn btn-danger btn-md" onclick="return confirm('{#deleteThreadConfirmation#}')">{#deleteThread#}</a></p>
		</div>
	</div>
</div>

{/block}

{block name=breadcrumb}

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
	<li><a href = "{$website_path}/messaging">{#messaging#}</a><span class="arrow"></span></li>
	<li class = "active">{#readThread#}</li>
</ol>

{/block}

{block name=body}

<div class="row">
	<div class="page-header"><h3>Sujet: {$messaging_threadTitle}</h3></div>
</div>

{foreach from = $messaging_messages item = message}

<div id="m{$message.msgInThread}" style="display:block;padding-top:130px; margin-top:-130px;"></div>
<div class="row">	
	<div class="col-lg-1"> 
		<a href="#"><img style="padding-bottom: 5px; height: 75px; width: 75px;" src="{$website_path}/{$message.avatarAuthor}" /></a>
		
		{if $message.levelAuthor == 1}
		
		<span class="label label-default">{#member#}</span>
		
		{elseif $message.levelAuthor == 2} 
		
		<span class="label label-success">{#moderator#}</span>
		
		{elseif $message.levelAuthor == 3}
		
		<span class="label label-danger">{#administrator#}</span>

		{/if}
		
	</div>
	<div class="col-lg-11">
		<div class="forum-box-default">
		
			<div class="box-header">
				<div class="row">
					<div class="col-lg-6" style="padding-left: 0px;">
						<span class="pull-left">
							<a href="#">{$message.usernameAuthor}</a> &nbsp; <span class="date"><a href="#">{$message.expeditionDate}</a></span>
						</span>
					</div>
				</div>
			</div>
			<div class="box-body">
				<p>{$message.content}</p>
			</div>
			
			{if !empty($message.signatureAuthor)}
			
			<div class="box-footer">
				<p>{$message.signatureAuthor}</p>
			</div>
			
			{/if}
			
		</div>
	</div>
</div>

<br />

{/foreach}

<div class="row">
	{$pagination}
</div>

{if $isLastPage}

<div class="row">
	<form class="form-horizontal" action="{$website_path}/messaging/thread/{$messaging_threadID}" method="post" name="messaging_newMessage">
			<div class="form-group">
				<div class="col-lg-12">
					<div class="editor">
						<h3>{#editor#}</h3>
						
						{$bbcodeEditor_buttons}
						
						<textarea name="messaging_messageContent" placeholder="{#msgContent#}" style="width: 100%;" rows="20">{if isset($forum_textAreaDefault)} {$forum_textAreaDefault} {/if}</textarea>
						
					</div>
				</div>
			</div>
			
			<div class="form-group"> 
				<div class="col-lg-12">
					<span class="pull-right"><button type="submit" class="btn btn-primary" name="post">{#send#}</button></span>
				</div>
			</div>
		</form>
</div>

{/if}

{literal}
<script type="text/javascript">
// <![CDATA[
var form_name = 'messaging_newMessage';
var text_name = 'messaging_messageContent';
// ]]>
</script>
{/literal}

{/block}	