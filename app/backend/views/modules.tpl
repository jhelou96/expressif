{extends file="web/templates/backend/{$website_backendTemplate}/layout.tpl"}
{config_load file="app/backend/lang/{$website_lang}/modules.php"}

{block name = title}{#modulesManager#} &bull; {#backend#} &bull;{/block}

{block name = body}

<div class="page-header"><h3>{#modulesManager#}</h3></div>
<div class="row">

    {foreach from = $modules item = $module}

    <div class="col-lg-3">
        <div class="panel panel-default">
            <div class="panel-heading">{$module.name}</div>
            <div class="panel-body text-center">{$module.description}</div>
            <ul class="list-group">
                <li class="list-group-item"><strong>{#type#}:</strong> <p class="pull-right">{if $module.isOfficial}{#official#}{else}{#notOfficial#}{/if}</p></li>
                <li class="list-group-item"><strong>{#author#}:</strong> <p class="pull-right">{$module.author}</p></li>
                <li class="list-group-item"><strong>{#compatibility#}:</strong> <p class="pull-right">{$module.compatibility}</p></li>
                <li class="list-group-item"><strong>{#PHPVersion#}:</strong> <p class="pull-right">{$module.php}</p></li>
                <li class="list-group-item"><strong>{#dependencies#}:</strong> <p class="pull-right">{foreach from = $module.dependencies item = $dependency name = dependencies}{$dependency}{if !$smarty.foreach.dependencies.last}, {/if} {foreachelse} {#none#} {/foreach}</p></li>

            </ul>
            <div class="panel-footer text-center">

                {if $module.isOfficial}

                    <a href="#" class="btn btn-sm btn-danger pull-left" disabled="disabled">{#remove#}</a>

                {else}

                <a href="{$module.removeModuleURL}" class="btn btn-sm btn-danger pull-left">{#remove#}</a>

                {/if}

                {if $module.activated}

                <a href="{$module.changeModuleStatusURL}" class="btn btn-sm btn-warning" onclick="return confirm('{#deactivateConfirmation#}');">{#deactivate#}</a>

                {else}

                <a href="{$module.changeModuleStatusURL}" class="btn btn-sm btn-success">{#activate#}</a>

                {/if}

                {if $module.manageModuleURL === null}

                <a href="#" class="btn btn-sm btn-primary pull-right" disabled="disabled">{#manage#}</a>

                {else}

                <a href="{$module.manageModuleURL}" class="btn btn-sm btn-primary pull-right">{#manage#}</a>

                {/if}

                <br /><br />
            </div>
        </div>
    </div>

   {foreachelse}

    <div class="alert alert-warning">
        <strong>{#warning#}!</strong> {#noActivatedModules#}
    </div>

    {/foreach}

</div>

{/block}