{extends file="../layout.tpl"}
{config_load file="app/modules/pages/lang/{$website_lang}/backend/listPages.php"}

{block name = title}{#pages#} &bull; {#modules#} &bull; {#backend#} &bull;{/block}

{block name = module_body}

<div class="col-lg-10">
    <div class="panel panel-default">
        <div class="panel-heading">{#pages#}</div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{#page#}</th>
                    <th>{#action#}</th>
                </tr>
            </thead>
            <tbody>

                {foreach from = $pages item = $page}

                <tr>
                    <td style="width:80%">{if $page.ishomepage}<i class="glyphicon glyphicon-home"></i>{/if} &nbsp; {$page.name}</td>
                    <td>
                        <a href="{$page.pageURL}" class="btn btn-xs btn-primary">{#see#}</a>
                        {if !$page.ishomepage}<a href="{$website_path}/backend/modules/manage/pages/{$page.id}/set-homepage" class="btn btn-xs btn-info">{#setAsHomepage#}</a>{/if}
                        <a href="{$website_path}/backend/modules/manage/pages/{$page.id}/edit" class="btn btn-xs btn-warning">{#edit#}</a>
                        {if !$page.ishomepage}<a href="{$website_path}/backend/modules/manage/pages/{$page.id}/remove" onclick="return confirm('{#removePageConfirmation#}');" class="btn btn-xs btn-danger">{#remove#}</a>{else}<a href="#" class="btn btn-xs btn-danger" disabled="disabled">{#remove#}</a>{/if}
                    </td>
                </tr>

                {foreachelse}

                    <tr><td colspan="4" class="text-center">{#noPagesCreated#}</td></tr>

                {/foreach}

            </tbody>
        </table>
    </div>
</div>

{/block}