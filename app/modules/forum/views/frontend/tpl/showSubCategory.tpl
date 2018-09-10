{extends file="../layout.tpl"}
{config_load file="app/modules/forum/lang/{$website_lang}/frontend/showSubCategory.php"}

{block name=title}{$forum_subCategoryName} &bull; {#title#} &bull; {/block}

{block name=breadcrumb}

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
	<li><a href = "{$website_path}/forum">{#forum#}</a><span class="arrow"></span></li>
	<li class = "active">{$forum_subCategoryName}</li>
</ol>

{/block}

{block name=body}

<div class="row">
	<div class="col-lg-12">
		<div class="page-header"><h3>{$forum_subCategoryName}</h3></div>
		<p>{$forum_subCategoryDesc}</p>
		
		{if $isConnected}<p class="text-right"><a href="{$website_path}/forum/{$forum_subCategoryURL}/action/new-topic" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-edit"></span> {#newSubject#}</a></p>{/if}
		
		{if isset($forum_topics)}
		
		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th>{#answers#}</th>
					<th>{#lastMsg#}</th>
				</tr>
			</thead>
			<tbody>
				
				{foreach from = $forum_topics.postit item = topic}
				
				<tr {if $topic.view eq 0} style="border-left: 6px solid orange;" {elseif $topic.view eq 1} style="border-left: 6px solid #31B404; font-weight: bold;" {elseif $topic.view eq 2} style="border-left: 6px solid #B40404; font-weight: bold;" {/if}>
					<td style="width:1%;"><img src="https://cdn0.iconfinder.com/data/icons/small-n-flat/24/678093-pin-24.png" /></td>
					<td width="70%">{if $topic.solved}<span class="label label-success">{#solved#}</span>{/if} <a href="{$website_path}/forum/{$forum_subCategoryURL}/{$topic.url}">{$topic.title}</a><br /><h6 style="color:#787878">{#by#} <a href="#">{$topic.author}</a> - {$topic.creationDate}</h6></td>
					<td>{$topic.nbAnswers}</td>
					<td>{$topic.lastMsg}</td>
				</tr>
				
				{/foreach}
				
				{if !empty($forum_topics.postit)}
				
				<tr style="height:50px;"></tr>
				
				{/if}
				
				{foreach from = $forum_topics.normal item = topic}
				
				<tr {if $topic.view eq 0} style="border-left: 6px solid orange;" {elseif $topic.view eq 1} style="border-left: 6px solid #31B404; font-weight: bold;" {elseif $topic.view eq 2} style="border-left: 6px solid #B40404; font-weight: bold;" {/if}>
					
					{if $topic.locked}
					
					<td style="width:1%;"><img src="https://cdn3.iconfinder.com/data/icons/streamline-icon-set-free-pack/48/Streamline-68-24.png" /></td>
					<td width="70%">{if $topic.solved}<span class="label label-success">{#solved#}</span>{/if} <a href="{$website_path}/forum/{$forum_subCategoryURL}/{$topic.url}">{$topic.title}</a><br /><h6 style="color:#787878;">{#by#} <a href="#">{$topic.author}</a> - {$topic.creationDate}</h6></td>
					
					{else}
					
					<td colspan="2" width="70%">{if $topic.solved}<span class="label label-success">{#solved#}</span>{/if} <a href="{$website_path}/forum/{$forum_subCategoryURL}/{$topic.url}">{$topic.title}</a><br /><h6 style="color:#787878;">{#by#} <a href="#">{$topic.author}</a> - {$topic.creationDate}</h6></td>
					
					{/if}
					
					<td>{$topic.nbAnswers}</td>
					<td>{$topic.lastMsg}</td>
				</tr>
				
				{/foreach}
				
			</tbody>
		</table>
		
		{$pagination}
		
		{/if}
		
	</div>
</div>

{/block}