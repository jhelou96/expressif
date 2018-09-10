{extends file="../layout.tpl"}
{config_load file="app/modules/articles/lang/{$website_lang}/frontend/showArticle.php"}

{block name=title}{$article_title} &bull; {#articles#} &bull; {/block}

{block name=breadcrumb}

	<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
		<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
		<li><a href = "{$website_path}/articles">{#articles#}</a><span class="arrow"></span></li>
		<li class="active">{$article_title}</li>
	</ol>

{/block}

{block name=rightsideMenu}

<div class="row">
	<a href="#" class="thumbnail">
		<img src="{$website_path}/app/modules/articles/views/frontend/images/{$article_thumbnail}" alt="{$article_title}" />
	</a>
</div>
<div class="row">
	<div class="panel panel-default">
		<div class="panel-heading">{#tableOfContent#}</div>
		{$article_tableContents}
	</div>
</div>	
<div class="row">
	<div class="panel panel-default">
		<div class="panel-heading">{#articleInformation#}</div>
		<ul class="list-group">
			<li class="list-group-item"><strong>{#author#}:</strong> <span class="pull-right"><a href="#">{$article_author}</a></span></li>
			<li class="list-group-item"><strong>{#publication#}:</strong> <span class="pull-right">{$article_publicationDate}</span></li>
			<li class="list-group-item"><strong>{#category#}:</strong> <span class="pull-right"><a href="#">{$article_category}</a></span></li>
		</ul>
	</div>
</div>

{if $isModerator}

<div class="row">
	<a href="{$website_path}/backend/modules/manage/articles/edit/{$article_id}" class="btn btn-block btn-lg btn-warning"><span class="glyphicon glyphicon-wrench"></span> Moderate</a>
</div>

{/if}
			
{/block}

{block name=body}

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">{$article_title}</div>
			<div class="panel-body">
				{$article_content}
			</div>
		</div>
	</div>
</div>

{/block}