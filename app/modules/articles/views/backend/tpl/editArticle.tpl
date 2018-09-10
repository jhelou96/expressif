{extends file="../layout.tpl"}
{config_load file="app/modules/articles/lang/{$website_lang}/backend/editArticle.php"}

{block name=title}{#moderateArticle#} &bull; {#articles#} &bull; {#modules#} &bull; {#backend#} &bull;{/block}

{block name=body}

<div class="row">
	<div class="col-lg-12">
		<div class="page-header"><h3>{#editArticle#}</h3></div>

		{if isset($article_preview)}

		<div class="panel panel-default">
			<div class="panel-heading">Preview</div>
			<div class="panel-body">{$article_preview}</div>
		</div>

		{/if}

		<form class="form-horizontal" action="{$website_path}/backend/modules/manage/articles/edit/{$article_id}" name="editArticle" method="post">
			<a href="{$website_path}/backend/modules/manage/articles" class="btn btn-default">{#returnBack#}</a>
			<button type="submit" class="btn btn-info pull-right" name="article_preview">{#preview#}</button>
			<div class="form-group">
				<div class="col-lg-12">
					<div class="editor">
						<h3>{#editor#}</h3>

                        {$bbcodeEditor_buttons}

						<textarea name="article_content" style="width: 100%;" rows="20" placeholder="{#articleContent#}">{$article_content}</textarea>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-12">
					<label for="article_category">{#articleTitle#}:</label>
					<input type="text" class="form-control" name="article_title" placeholder="{#articleTitle#}" value="{$article_title}">
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-12">
					<label for="article_category">{#articleDescription#}:</label>
					<input type="text" class="form-control" name="article_description" placeholder="{#articleDescription#}" value="{$article_description}">
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-12">
					<label for="article_category">{#category#}:</label>
					<select class="form-control" name="article_category">

                        {foreach from = $categories item = category}

							<option value="{$category.id}" {(isset($article_category) && $article_category == $category.id) ? 'selected="selected"' : ''}>{$category.name}</option>

                        {/foreach}

					</select>
				</div>
			</div>
			
			<div class="form-group"> 
				<div class="col-lg-6">
					<a href="{$website_path}/backend/modules/manage/articles/refuse/{$article_id}" onclick="return confirm('{#refuseArticleConfirmation#}')" class="btn btn-block btn-danger">{#refuseArticle#}</a>
				</div>
				<div class="col-lg-6">
					<button type="submit" class="btn btn-success btn-block" name="article_validate">{#validateArticle#}</button>
				</div>
			</div>
		</form>
	</div>
</div>

{literal}
<script type="text/javascript">
// <![CDATA[
var form_name = 'editArticle';
var text_name = 'article_content';
// ]]>
</script>
{/literal}

{/block}