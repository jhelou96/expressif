{extends file="../layout.tpl"}
{config_load file="app/modules/articles/lang/{$website_lang}/frontend/myArticles.php"}

{block name=title}{#myArticles#} &bull; {/block}

{block name=breadcrumb}

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
	<li class="active">{#myArticles#}</li>
</ol>

{/block}

{block name=rightsideMenu}{/block}

{block name=leftsideMenu}{/block}

{block name=body}

<div class="row">
	<div class="col-lg-12">
		
		<a href="{$website_path}/articles/my-articles/write" class="btn btn-block btn-lg btn-warning"><span class="glyphicon glyphicon-edit"></span> {#writeArticle#}</a>
		<br />
		
		<div class="panel panel-default">
			<div class="panel-heading">{#myArticles#}</div>
			<table class="table table-striped list-articles">
				<tbody>
					
					{foreach from = $articles item = article}
					
					<tr>
						{if $article.status eq 0}
						
						<td class="status"><span class="label label-default">{#draft#}</span></td>
						
						{elseif $article.status eq 1}
						
						<td class="status"><span class="label label-primary">{#beingValidated#}</span></td>
						
						{elseif $article.status eq 2}
						
						<td class="status"><span class="label label-success">{#published#}</span></td>
						
						{else}
						
						<td class="status"><span class="label label-pill label-danger">{#refused#}</span></td>
						
						{/if}
						
						<td class="title">{$article.title}</td>
						
						{if isset($article.url)}
						
						<td class="action"><a href="{$article.url}" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-globe"></span> {#see#}</a></td>
						
						{else}
						
						<td class="action"><a href="#" class="btn btn-sm btn-success" disabled="disabled"><span class="glyphicon glyphicon-globe"></span> {#see#}</a></td>
						
						{/if}
						
						{if $article.status eq 0}
						
						<td class="action"><a href="{$website_path}/articles/my-articles/edit/{$article.id}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-pencil"></span> {#edit#}</a></td>
						
						{else}
						
						<td class="action"><a href="#" class="btn btn-sm btn-primary" disabled="disabled"><span class="glyphicon glyphicon-pencil"></span> {#edit#}</a></td>
						
						{/if}
						
						{if $article.status eq 0}
						
						<td class="action"><a href="{$website_path}/articles/my-articles/remove/{$article.id}" onClick="return(confirm('Êtes-vous sûr de vouloir supprimer cet article ?'));" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {#remove#}</a></td>
						
						{else}
						
						<td class="action"><a href="#" class="btn btn-sm btn-danger" disabled="disabled"><span class="glyphicon glyphicon-trash"></span> {#remove#}</a></td>
						
						{/if}
						
					</tr>
					
					{foreachelse}
				
					<tr>
						<td class="text-center"><i>{#noPublishedArticles#}</i></td>
					</tr>
				
					{/foreach}
					
				</tbody>
			</table>
		</div>
	</div>
</div>

{/block}