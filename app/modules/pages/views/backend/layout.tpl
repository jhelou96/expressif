{extends file="web/templates/backend/{$website_backendTemplate}/layout.tpl"}
{config_load file="app/modules/pages/lang/{$website_lang}/backend/layout.php"}
{config_load file="app/modules/pages/lang/{$website_lang}/alerts.php"}

{block name = header}

<!-- BBCode editor -->
<link rel="stylesheet" href="{$website_path}/vendor/editor/bbcode.css" />
<script type="text/javascript" src="{$website_path}/vendor/editor/bbcode.js"></script>

<!-- Syntax highlighting -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/highlight.js/9.5.0/styles/default.min.css">
<script src="//cdn.jsdelivr.net/highlight.js/9.5.0/highlight.min.js"></script>

{literal}
<script>hljs.initHighlightingOnLoad();</script>
{/literal}

{block name = module_header}{/block}

{/block}

{block name = body}

<div class="page-header"><h3>{#pagesModuleManager#}</h3></div>
<div class="row">
<div class="col-lg-2">
    <div class="panel panel-default">
        <div class="panel-heading">{#moduleMenu#}</div>
        <ul class="list-group">
            <li class="list-group-item"><a href="{$website_path}/backend/modules/manage/pages"><strong>{#managePages#}</strong></a></li>
            <li class="list-group-item"><a href="{$website_path}/backend/modules/manage/pages/new">{#createPage#}</a></li>
        </ul>
    </div>
</div>

{block name = module_body}{/block}
</div>

{/block}