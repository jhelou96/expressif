{extends file="web/templates/backend/{$website_backendTemplate}/layout.tpl"}
{config_load file="app/backend/lang/{$website_lang}/dashboard.php"}

{block name = title}{#dashboard#} &bull; {#backend#} &bull;{/block}

{block name = body}

<div class="page-header"><h3>{#dashboard#}</h3></div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">{#welcomeMessage#}</div>
            <div class="panel-body">
                <p>
                    {#adminPanelDesc#} <br />
                    {#readAdvices#}
                </p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">

			{if $system_updateAvailable}

			<blockquote class="blockquote" style="border-left: 5px solid #9F2929; background-color: #F2F2F2;">
				<p class="mb-0" style="color: #9F2929;">{#updateAvailable#}</p>
				<footer class="blockquote-footer"><a href="{$website_path}/update.php" class="link">{#checkUpdate#}</a></footer>
			</blockquote>
			
			{else}

			<blockquote class="blockquote" style="border-left: 5px solid #81B964; background-color: #F2F2F2;">
				<p class="mb-0" style="color: #81B964;">{#systemUpToDate#}</p>
			</blockquote>

			{/if}
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">{#systemInformation#}</div>
            <ul class="list-group">
                <li class="list-group-item"><strong>OS:</strong> <p class="pull-right">{$system_os}</p></li>
                <li class="list-group-item"><strong>PHP:</strong> <p class="pull-right">{$system_php}</p></li>
                <li class="list-group-item"><strong>{#database#}:</strong> <p class="pull-right">{$system_db}</p></li>
                <li class="list-group-item"><strong>{#webserver#}:</strong> <p class="pull-right">{$system_webserver}</p></li>
                <li class="list-group-item"><strong>{#softwareVersion#}:</strong> <p class="pull-right">{$system_softwareVersion}</p></li>
            </ul>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">{#advices#}</div>
            <ul class="list-group">
                <li class="list-group-item">{#softwareShouldBeUpToDate#}</li>
                <li class="list-group-item">{#modulesNotUsedShouldBeDeactivated#}</li>
                <li class="list-group-item">{#databaseShouldBeBackedUp#}</li>
                <li class="list-group-item">{#modulesShouldBeCompatibleWithSoftwareVersion#}</li>
                <li class="list-group-item">{#bugsShouldBeReported#}</li>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">{#notepad#}</div>
            <div class="alert alert-info">
                <strong>{#info#}!</strong> {#notepadDesc#}
            </div>
            <form action="{$website_path}/backend" method="post">
                <div class="panel-body">
                    <div class="form-group">
                        <textarea class="form-control" rows="8" name="note" placeholder="Use this text box to save your notes.">{if isset($note)}{$note}{/if}</textarea>
                    </div>
                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-primary pull-right">{#submit#}</button>
                    <br /><br />
                </div>
            </form>
        </div>
    </div>
</div>

{/block}