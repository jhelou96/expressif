{extends file="../layout.tpl"}
{config_load file="app/modules/contact/lang/{$website_lang}/frontend/contact.php"}

{block name=title}{#contact#} &bull; {/block}

{block name=breadcrumb}

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
	<li class = "active">{#contact#}</li>
</ol>

{/block}

{block name=body}

<div class="row">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-lg-12">
				<div class="page-header"><h3>{#contactUs#}</h3></div>
				<form action="{$website_path}/contact" method="post">
				  <div class="form-group">
						<label for="contact_email">{#yourEmailAddress#}:</label>
						<input type="email" class="form-control" id="contact_email" name="contact_email" placeholder="{#emailAddress#}">
				  </div>
				  <div class="form-group">
						<label for="contact_email">{#subjectOfYourMessage#}:</label>
						<input type="text" class="form-control" id="contact_subject" name="contact_subject" placeholder="{#subject#}">
				  </div>
				  <div class="form-group">
						<textarea class="form-control" name="contact_message" placeholder="{#message#}" rows="10"></textarea>
				  </div>
				  <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-send"></span> {#send#}</button>
				</form>
			</div>
		</div>	
	</div>
</div>

{/block}