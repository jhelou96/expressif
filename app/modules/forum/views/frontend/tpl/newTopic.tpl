{extends file="../layout.tpl"}
{config_load file="app/modules/forum/lang/{$website_lang}/frontend/newTopic.php"}

{block name=title}{#newThread#} &bull; {#title#} &bull; {/block}

{block name=leftsideMenu}{/block}
{block name=rightsideMenu}{/block}

{block name=breadcrumb}

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
	<li><a href = "{$website_path}/forum">{#forum#}</a><span class="arrow"></span></li>
	<li><a href = "{$website_path}/forum/{$forum_subCategoryURL}">{$forum_subCategoryName}</a><span class="arrow"></span></li>
	<li class = "active">{#newThread#}</li>
</ol>

{/block}

{block name=body}

<div class="row">
	<div class="col-lg-12">
		<div class="page-header"><h3>{#pageHeader#}</h3></div>
		
		{if isset($forum_previewMsg)}
		
		<div class="row">
			<div class="col-lg-1">
				<p>{#preview#}: <i>{#msgNotPublishedYet#}</i></p>
			</div>
			<div class="col-lg-11">
				<strong>{#warningHowToSendMsg#}</strong>
				<div class="forum-box-default">
					<div class="box-body">
						<p>{$forum_previewMsg}</p>
					</div>
				</div>
			</div>
		</div>
		<br />
		
		{/if}
		
		<div class="row">
			<form class="form-horizontal" action="{$forum_postURL}" method="post" name="forum_newTopic">
				<div class="form-group">
					<div class="col-lg-12">
						<input type="text" class="form-control" name="forum_topicTitle" placeholder="{#threadTitle#}" {if isset($forum_topicTitleDefault)} value="{$forum_topicTitleDefault}" {/if} />  
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-lg-12">
						<div class="editor">
							<h3>{#editor#}</h3>
							
							{$bbcodeEditor_buttons}
							
							<textarea name="forum_topicMessage" placeholder="{#msgContent#}" style="width: 100%;" rows="20">{if isset($forum_topicMessageDefault)} {$forum_topicMessageDefault} {/if}</textarea>
							
						</div>
					</div>
				</div>
				
				<div class="form-group"> 
					<div class="col-lg-12">
						<span class="pull-right"><button type="submit" class="btn btn-default" name="preview">{#preview#}</button> &nbsp; <button type="submit" class="btn btn-primary" name="post">{#send#}</button></span>
					</div>
				</div>
			</form>	
		</div>
	</div>
</div>

{literal}
<script type="text/javascript">
// <![CDATA[
var form_name = 'forum_newTopic';
var text_name = 'forum_topicMessage';
// ]]>
</script>
{/literal}

{/block}