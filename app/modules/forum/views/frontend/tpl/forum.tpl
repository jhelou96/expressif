{extends file="../layout.tpl"}
{config_load file="app/modules/forum/lang/{$website_lang}/frontend/forum.php"}

{block name=title}{#title#} &bull; {/block}

{block name=breadcrumb}

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
	<li class = "active">{#forum#}</li>
</ol>

{/block}

{block name=body}

<div class="row">
	<div class="col-lg-12">
		<div class="page-header"><h3>{#pageHeader#}</h3></div>
		<p>{#forumDesc#}</p>
		
		{foreach from = $forum_categories item = category}
		
		<div class="page-header"><h3>{$category.name}</h3></div>
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="60%"></th>
					<th width="10%">{#subjects#}</th>
					<th width="10%">{#messages#}</th>
					<th width="20%">{#lastMessage#}</th>
				</tr>
			</thead>
			<tbody style="border-left: 6px solid orange;">
				
				{foreach from = $forum_subCategories item = subCategory}
				
				{if $category.id eq $subCategory.idCategory}
		
				<tr>
					<td><a href="{$website_path}/forum/{$subCategory.url}">{$subCategory.name}</a> <br /><h6><i>{$subCategory.description}</i></h6></td>
					<td>{$subCategory.nbTopics}</td>
					<td>{$subCategory.nbMessages}</td>
					<td>
						{if isset($subCategory.lastMsgPosted)}
						
						<a href="{$website_path}/forum/{$subCategory.topicWithMostRecentMsgURL}">{$subCategory.lastMsgPosted}<br />{$subCategory.topicWithMostRecentMsg}</a>
						
						{else}
						
						{#noMsgFound#}
						
						{/if}
					</td>
				</tr>
				
				{/if}
				
				{foreachelse}
				
				
				<div class="alert alert-warning">
					<strong>{#warning#}</strong> {#noCategoriesFound#}
				</div>

				{/foreach}
				
			</tbody>
		</table>
		
		{foreachelse}
				
				
		<div class="alert alert-warning">
			<strong>{#warning#}</strong> {#noCategoriesFound#}
		</div>
		
		{/foreach}
	
	</div>
</div>

{/block}