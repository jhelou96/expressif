{extends file="web/templates/frontend/{$website_frontendTemplate}/layout.tpl"}
{config_load file="app/modules/messaging/lang/{$website_lang}/alerts.php"}

{block name = header}
<!-- Module related CSS files -->
<link rel="stylesheet" href="{$website_path}/app/modules/messaging/views/frontend/css/messaging.css" />

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

{block name=leftsideMenu}{/block}
{block name=rightsideMenu}{/block}