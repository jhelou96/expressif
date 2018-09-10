{extends file="../layout.tpl"}
{config_load file="app/modules/pages/lang/{$website_lang}/backend/editPage.php"}

{block name=title}{#editPage#} &bull; {#pages#} &bull; {#modules#} &bull; {#backend#} &bull;{/block}

{block name=body}

<div class="row">
	<div class="col-lg-12">
		<div class="page-header"><h3>{#editPage#}</h3></div>

		<form class="form-horizontal" action="{$website_path}/backend/modules/manage/pages/{$page_id}/edit" name="editPage" method="post">
			<div class="form-group">
				<div class="col-lg-12">
					<label for="page_name">{#pageName#}:</label>
					<input type="text" class="form-control" name="page_name" placeholder="{#pageName#}" {if isset($page_name)}value="{$page_name}"{/if}>
				</div>
			</div>

			<div class="form-group">
				<div class="col-lg-12">
					<div class="editor">
						<h3>{#editor#}</h3>

                        {$bbcodeEditor_buttons}

						<textarea name="page_content" style="width: 100%;" rows="20" placeholder="{#pageContent#}">{if isset($page_content)}{$page_content}{/if}</textarea>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-lg-12">
					<a href="{$website_path}/backend/modules/manage/pages" class="btn btn-default btn-lg">{#returnBack#}</a>
					<button type="submit" class="btn btn-success btn-lg pull-right">{#editPage#}</button>
				</div>
			</div>
		</form>
	</div>
</div>

{literal}
<script type="text/javascript">
// <![CDATA[
var form_name = 'editPage';
var text_name = 'page_content';
// ]]>
</script>
{/literal}

{/block}