{extends file="web/templates/backend/{$website_backendTemplate}/layout.tpl"}
{config_load file="app/modules/members/lang/{$website_lang}/backend/layout.php"}
{config_load file="app/modules/members/lang/{$website_lang}/alerts.php"}

{block name = header}

{block name = module_header}{/block}

{/block}

{block name = body}

<div class="page-header"><h3>{#membersModuleManager#}</h3></div>
<div class="row">
<div class="col-lg-2">
    <div class="panel panel-default">
        <div class="panel-heading">{#moduleMenu#}</div>
        <ul class="list-group">
            <li class="list-group-item"><a href="{$website_path}/backend/modules/manage/members"><strong>{#manageMembers#}</strong></a></li>
        </ul>
    </div>
</div>

{block name = module_body}{/block}
</div>

{/block}