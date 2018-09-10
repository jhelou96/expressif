{extends file="../layout.tpl"}
{config_load file="app/modules/members/lang/{$website_lang}/frontend/settings.php"}

{block name=title}{#settings#} &bull; Members &bull; {/block}

{block name=rightsideMenu}{/block}
{block name=leftsideMenu}{/block}

{block name=breadcrumb}

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
	<li><a href = "#">{#members#}</a><span class="arrow"></span></li>
	<li class = "active">{#settings#}</li>
</ol>

{/block}

{block name=body}

<div class="alert alert-warning">
  <strong>{#warning#}</strong> {#leavePswdEmptyIfNoChangesWanted#}
</div>

<form action="{$website_path}/members/settings" method="post" enctype="multipart/form-data">
	<div class="form-group">
		<div class="row">
			<div class="col-lg-12">
				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
					<input type="text" class="form-control" name="settings_email" placeholder="{#email#}" value="{$member_email}">
				</div>
			</div>
		</div>
		<br />
		<div class="row">
			<div class="col-lg-6">
				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
					<input type="password" class="form-control" name="settings_password" placeholder="{#password#}">
				</div>
			</div>
			<div class="col-lg-6">
				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
					<input type="password" class="form-control" name="settings_passwordConfirmation" placeholder="{#passwordConfirmation#}">
				</div>
			</div>
		</div>
		<br />
		<div class="row">
			<div class="col-lg-12">
				<div class="form-group">
					<textarea class="form-control" rows="3" name="settings_signature" placeholder="{#signature#}">{$member_signature}</textarea>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
					<label>Avatar:</label>
					<input type="file" class="form-control" name="settings_avatar" accept="image/*">
			</div>
		</div>
		<br />
		<button type="submit" class="btn btn-primary btn-md btn-block">{#save#}</button>
	</div>
</form>

{/block}