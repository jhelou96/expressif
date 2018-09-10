{extends file="../layout.tpl"}
{config_load file="app/modules/articles/lang/{$website_lang}/frontend/showCategory.php"}

{block name=title}{$articles_categoryName} &bull; {#articles#} &bull; {/block}

{block name=breadcrumb}

	<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
		<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
		<li><a href = "{$website_path}/articles">{#articles#}</a><span class="arrow"></span></li>
		<li class="active">{$articles_categoryName}</li>
	</ol>

{/block}

{block name=body}

<div class="row">
	<div class="col-lg-12">
		<div class="page-header"><h3><h3>{#category#}: <span style="color: orange;">{$articles_categoryName}</span></h3></h3></div>
		<div class="col-lg-12">
		
			{foreach from = $articles item = article}

				<ul class="list-group">
					<li class="list-group-item">
						<div class="row" style="height: 100%; display: table-row;">
							<div class="col-lg-2" style="display: table-cell; float: none; vertical-align: middle;">
								<img class="img-thumbnail" src="{$website_path}/app/modules/articles/views/frontend/images/{$article.thumbnail}" />
							</div>
							<div class="col-lg-10">
								<h4>
									<a href="{$article.articleURL}">{$article.title}</a>
									<br />
									<small>{$article.description}</small>
								</h4>
								<h6>
									<strong style="color: orange;">{#category#}:</strong> <a href="{$article.categoryURL}">{$article.category}</a> <br />
									<strong style="color: orange;">{#author#}:</strong> <a href="#">{$article.author}</a> <br />
									<strong style="color: orange;">{#publicationDate#}:</strong> {$article.publicationDate}
								</h6>
							</div>
						</div>
					</li>
				</ul>

			{foreachelse}

			<div class="alert alert-warning">
				<strong>{#warning#}</strong> {#noArticlesPublished#}
			</div>

			{/foreach}
			
			{$pagination}
			
		</div>
	</div>
</div>

{/block}