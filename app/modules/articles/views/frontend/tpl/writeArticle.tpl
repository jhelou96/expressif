{extends file="../layout.tpl"}
{config_load file="app/modules/articles/lang/{$website_lang}/frontend/writeArticle.php"}

{block name=title}{#newArticle#} &bull; {#myArticles#} &bull; {/block}

{block name=breadcrumb}

	<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
		<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
		<li><a href = "{$website_path}/articles/my-articles">{#myArticles#}</a><span class="arrow"></span></li>
		<li class="active">{#newArticle#}</li>
	</ol>

{/block}

{block name=rightsideMenu}{/block}

{block name=leftsideMenu}{/block}

{block name=body}

<div class="row">
	<div class="col-lg-12">
		<div class="page-header"><h3>{#newArticle#}</h3></div>
		<form class="form-horizontal" action="{$website_path}/articles/my-articles/write" method="post" name="writeArticle">
			<div class="form-group">
				<div class="col-lg-12">
					<div class="editor">
						<h3>{#editor#}</h3>
						
						{$bbcodeEditor_buttons}
						
						<textarea name="article_content" style="width: 100%;" rows="20" placeholder="{#content#}">{if isset($article_content)} {$article_content} {/if}</textarea>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-12">
					<input type="text" class="form-control" name="article_title" placeholder="{#title#}" {if isset($article_title)} value="{$article_title}" {/if}>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-12">
					<input type="text" class="form-control" name="article_description" placeholder="{#description#}" {if isset($article_description)} value="{$article_description}" {/if}>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-12">
					<label for="article_category">{#category#}:</label>
					<select class="form-control" name="article_category">

						{foreach from = $article_categories item = category}

						<option value="{$category.id}" {(isset($article_category) && $article_category == $category.id) ? 'selected="selected"' : ''}>{$category.name}</option>

						{/foreach}
						
					</select>
				</div>
			</div>

			<div class="form-group"> 
				<div class="col-lg-6">
					<button type="submit" class="btn btn-success btn-block" name="submit">{#submitArticle#}</button>
				</div>
				<div class="col-lg-6">
					<button type="submit" class="btn btn-primary btn-block" name="save">{#saveForLater#}</button>
				</div>
			</div>
		</form>
	</div>
</div>

{literal}
<script type="text/javascript">
// <![CDATA[
var form_name = 'writeArticle';
var text_name = 'article_content';
// ]]>
</script>
{/literal}

{/block}