{extends file="../layout.tpl"}
{config_load file="app/modules/messaging/lang/{$website_lang}/frontend/newThread.php"}

{block name=title}{#newThread#} &bull; {#messaging#} &bull; {/block}

{block name=breadcrumb}

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
	<li><a href = "{$website_path}/messaging">{#messaging#}</a><span class="arrow"></span></li>
	<li class = "active">{#newThread#}</li>
</ol>

{/block}

{block name=body}

<div class="row">
	<div class="page-header"><h3>{#newThread#}</h3></div>
</div>
<div class="row">
	<form class="form-horizontal" action="{$website_path}/messaging/thread/new" method="post" name="messaging_newMessage">
			<div class="form-group">
				<div class="col-lg-12">
					 <input type="text" class="form-control" name="messaging_threadParticipants" placeholder="{#recipients#}" {if isset($messaging_threadParticipants)}value="{$messaging_threadParticipants}"{/if} {if isset($messaging_username)}value="{$messaging_username}"{/if}>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-12">
					 <input type="text" class="form-control" name="messaging_threadTitle" placeholder="{#threadTitle#}" {if isset($messaging_threadTitle)}value="{$messaging_threadTitle}"{/if}>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-12">
					<div class="editor">
						<h3>{#editor#}</h3>
						
						{$bbcodeEditor_buttons}
						
						<textarea name="messaging_messageContent" placeholder="{#msgContent#}" style="width: 100%;" rows="20">{if isset($messaging_messageContent)}{$messaging_messageContent}{/if}</textarea>
						
					</div>
				</div>
			</div>
			
			<div class="form-group"> 
				<div class="col-lg-12 text-right">
					<button type="submit" class="btn btn-primary" name="post">{#send#}</button></span>
				</div>
			</div>
		</form>
</div>

{literal}
<script type="text/javascript">
// <![CDATA[
var form_name = 'messaging_newMessage';
var text_name = 'messaging_messageContent';
// ]]>
</script>
{/literal}

{/block}	