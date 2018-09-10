{extends file="../layout.tpl"}
{config_load file="app/modules/articles/lang/{$website_lang}/frontend/search.php"}

{block name=title}{#search#} &bull; {#articles#}{/block}

{block name=breadcrumb}

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
	<li><a href = "{$website_path}/forum">{#articles#}</a><span class="arrow"></span></li>
	<li class = "active">{#search#}</li>
</ol>

{/block}

{block name=body}
{if isset($search_articles)}

<div class="row">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">{#searchResults#}</div>
					<table class="table table-hover">
						<tbody>

							{foreach from = $search_articles item = article}

							<tr>
								<td style="width:10%"><img class="img-responsive" src="{$website_path}/app/modules/articles/views/frontend/images/search.png"></td>
								<td><h4 style="padding-top:15px;">
										<a href="{$article.url}">{$article.title}</a> <br />
										<small>Category - {$article.category}</small> <br />
										<small>{$article.description}</small>
									</h4>
								</td>
							</tr>

							{foreachelse}

							<tr><td class="text-center"><i>{#noArticlesFound#}</i></td></tr>

							{/foreach}

						</tbody>
					</table>
				</div>
			</div>
		</div>	
	</div>
</div>

{/if}
{/block}