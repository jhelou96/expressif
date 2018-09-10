{extends file="../layout.tpl"}
{config_load file="app/modules/members/lang/{$website_lang}/frontend/resetPassword.php"}

{block name=title}{#resetPassword#} &bull; {#members#} &bull; {/block}
{block name=rightsideMenu}{/block}
{block name=leftsideMenu}{/block}

{block name=breadcrumb}

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
	<li><a href = "#">{#members#}</a><span class="arrow"></span></li>
	<li class = "active">{#resetPassword#}</li>
</ol>

{/block}

{block name=body}

<div class="row">
	<div class="col-lg-12">
		<div class="page-header"><h3>{#resetPassword#}</h3></div>
		<form action="{$website_path}/members/reset-password/{$resetPassword_token}" method="post">
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
					<input type="password" class="form-control" name="resetPassword_password" placeholder="{#password#}">
				</div>
				<br />
				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
					<input type="password" class="form-control" name="resetPassword_confirmation" placeholder="{#passwordConfirmation#}">
				</div>
			</div>
			<br />
			<button type="submit" class="btn btn-primary btn-md btn-block">{#reset#}</button>
		</form>
	</div>
</div>

{/block}	