{extends file="../layout.tpl"}
{config_load file="app/modules/pages/lang/{$website_lang}/frontend/page.php"}

{block name=title}{$page_name} &bull; {/block}

{block name=breadcrumb}
{if !$page_isHomepage}

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
	<li class = "active">{$page_name}</li>
</ol>

{/if}
{/block}

{block name=body}

<div class="row">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">{$page_name}</div>
					<div class="panel-body">{$page_content}</div>
				</div>
			</div>
		</div>	
	</div>
</div>

{/block}