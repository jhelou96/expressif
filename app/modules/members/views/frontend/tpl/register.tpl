{extends file="../layout.tpl"}
{config_load file="app/modules/members/lang/{$website_lang}/frontend/register.php"}

{block name=title}{#register#} &bull; {#members#} &bull; {/block}		
{block name=rightsideMenu}{/block}
{block name=leftsideMenu}{/block}

{block name=breadcrumb}

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
	<li><a href = "#">{#members#}</a><span class="arrow"></span></li>
	<li class = "active">{#register#}</li>
</ol>

{/block}

{block name=body}

<div class="row">
	<div class="alert alert-info">
		<strong>{#alreadyRegistered#}</strong> <a href="{$website_path}/members/login" class="link">{#linkToConnect#}</a>.
	</div>
</div>
<div class="row">
	<div class="col-lg-6">
		<div class="page-header"><h3>{#registerToday#}</h3></div>
		<form action="{$website_path}/members/register" method="post">
			<div class="form-group">

				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
					<input type="text" class="form-control" name="register_username" {if isset($register_username)} value="{$register_username}" {else} placeholder="{#username#}" {/if}>
				</div>

				<br />

				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
					<input type="email" class="form-control" name="register_email" {if isset($register_email)} value="{$register_email}" {else} placeholder="{#email#}" {/if}>
				</div>

				<br />

				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
					<input type="password" class="form-control" name="register_password" placeholder="{#password#}">
				</div>

				<div class="checkbox checkbox-primary">
					<input type="checkbox" name="register_rules" checked>
					<label for="register_rules">
                        {#IAgreeTo#} <a href="{$website_path}/pages/rules" class="link">{#rules#}</a>
					</label>

					<span class="pull-right">
						<a href="#" class="link">{#activationMailNotRecieved#}</a>
					</span>
				</div>
			</div>
			<input type="submit" class="btn btn-primary btn-md btn-block" value="Register" />
		</form>
	</div>
	<div class="col-lg-6">
		<div class="page-header"><h3>{#reasonsToRegister#}</h3></div>
		<ul class="registrationList">
			<li><strong>{#timeToRegister#}</strong></li>
			<li>{#participateOnForums#}</li>
			<li>{#commentArticles#}</li>
			<li>{#writeArticles#}</li>
			<li>{#andMore#}</li>
		</ul>
	</div>
</div>

{/block}	