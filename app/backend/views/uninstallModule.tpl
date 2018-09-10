{extends file="web/templates/backend/{$website_backendTemplate}/layout.tpl"}
{config_load file="app/backend/lang/{$website_lang}/uninstallModule.php"}

{block name = title}{#moduleUninstaller#} &bull; {#modulesManager#} &bull; {#backend#} &bull;{/block}

{block name = body}

<div class="page-header"><h3>{#modulesManager#}</h3></div>
<div class="row">
    <div class="col-lg-9">
        <div class="panel panel-default">
            <div class="panel-heading">{#moduleUninstallerTool#}</div>
            <div class="alert alert-info">
                 <strong>{#info#}!</strong> <strong><u>{$module}</u>:</strong> {#moduleUninstallationInfo#}
            </div>
            <div class="panel-body">
                <p class="text-center"><a href="{$website_path}/backend/modules/uninstall/complete/{$module}" class="btn btn-warning" onclick="return confirm('{#moduleUninstallationConfirmation#}');">{#completeUninstallation#}</a> <a href="{$website_path}/backend/modules/uninstall/simple/{$module}" class="btn btn-info" onclick="return confirm('{#moduleUninstallationConfirmation#}');">{#simpleUninstallation#}</a></p>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-default">
            <div class="panel-heading">{#uninstallationTypes#}</div>
            <ul class="list-group">
                <li class="list-group-item"><strong>{#simpleUninstallation#}</strong>: {#simpleUninstallationDescription#}. <i>{#simpleUninstallationUtility#}.</i></li>
                <li class="list-group-item"><strong>{#completeUninstallation#}</strong>: {#completeUninstallationDescription#}.</li>
            </ul>
        </div>
    </div>
</div>

{/block}