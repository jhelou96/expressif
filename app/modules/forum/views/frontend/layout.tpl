{extends file="web/templates/frontend/{$website_frontendTemplate}/layout.tpl"}
{config_load file="app/modules/forum/lang/{$website_lang}/alerts.php"}

{block name = header}
<!-- Module related CSS files -->
<link rel="stylesheet" href="{$website_path}/app/modules/forum/views/frontend/css/forum.css" />

<!-- BBCode editor -->
<link rel="stylesheet" href="{$website_path}/vendor/editor/bbcode.css" />
<script type="text/javascript" src="{$website_path}/vendor/editor/bbcode.js"></script>

<!-- Syntax highlighting -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/highlight.js/9.5.0/styles/default.min.css">
<script src="//cdn.jsdelivr.net/highlight.js/9.5.0/highlight.min.js"></script>

{literal}
<script>hljs.initHighlightingOnLoad();</script>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
{/literal}

{block name = module_header}{/block}

{/block}

{block name = leftsideMenu}

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">Search</div>
        <div class="panel-body">
            <form action="{$website_path}/forum/search" method="post" name="search">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" name="forum_search" placeholder="Search">
                        <div class="input-group-addon"><a href="#" onclick="document.forms['search'].submit();"><span class="glyphicon glyphicon-search"></span></a></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{/block}