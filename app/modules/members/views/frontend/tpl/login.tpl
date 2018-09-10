{extends file="../layout.tpl"}
{config_load file="app/modules/members/lang/{$website_lang}/frontend/login.php"}

{block name=title}{#login#} &bull; {#members#} &bull; {/block}		
{block name=rightsideMenu}{/block}
{block name=leftsideMenu}{/block}

{block name=breadcrumb}

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
	<li><a href = "#">{#members#}</a><span class="arrow"></span></li>
	<li class = "active">{#login#}</li>
</ol>

{/block}

{block name=body}

<div class="row">
	<div class="alert alert-info">
		<strong>{#notRegisteredYet#}</strong> <a href="{$website_path}/membrers/register" class="link">{#linkToRegister#}</a>.
	</div>
	
	<div class="col-lg-6">
		<div class="page-header"><h3>{#classicConnection#}</h3></div>
		<form action="{$website_path}/members/login" method="post">
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
					<input type="text" class="form-control" name="login_username" placeholder="{#username#}" {if isset($login_username)} value="{$login_username}" {/if}>
				</div>
				<br />
				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
					<input type="password" class="form-control" name="login_password" placeholder="{#password#}" {if isset($login_password)} value="{$login_password}" {/if}>
				</div>
				<div class="checkbox checkbox-primary">
					<input type="checkbox" name="login_remember" {if isset($login_remember)} checked {/if}>
					<label for="login_remember">
						{#rememberMe#}
					</label>
					
					<span class="pull-right">
						<a href="#" class="link" data-toggle="modal" data-target="#passwordForgottenModal">{#passwordForgotten#}</a> <br />
						<a href="{$website_path}/members/register" class="link">{#notRegisteredYet#}</a>
					</span>
				</div>
			</div>
			<br />
			<button type="submit" class="btn btn-primary btn-md btn-block">{#login#}</button>
		</form>
	</div>
							
	<div class="col-lg-6">
		<div class="page-header"><h3>{#connectionViaSocialMedias#}</h3></div>
		<a class="btn btn-block btn-social btn-facebook" disabled="disable"> <span class="fa fa-facebook"></span> {#connectionFacebook#}</a><br/>
		<a class="btn btn-block btn-social btn-google" disabled="disable"> <span class="fa fa-google"></span> {#connectionGoogle#}</a><br/>
		<a class="btn btn-block btn-social btn-twitter" disabled="disable"> <span class="fa fa-twitter"></span> {#connectionTwitter#}</a><br />
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="passwordForgottenModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<form action="{$website_path}/members/reset-password" method="post">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#F3F3F3;">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title"><h3 style="font-family: 'Comic Sans MS'">{#passwordForgottenTitle#}</h3>
				</div>
				<div class="alert alert-info" role="alert">
					<strong>{#info#}!</strong> {#uniqueKeyWillBeSentByMail#}
				</div>
				<div class="modal-body">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
							<input type="email" class="form-control" name="passwordForgotten_email" placeholder="{#email#}">
						</div>
					</div>
				</div>
				<div class="modal-footer" style="background-color:#F3F3F3;">
					<button type="submit" class="btn btn-default">{#submit#}</button>
				</div>
			</div>
		</form>
	</div>
</div>

{/block}	