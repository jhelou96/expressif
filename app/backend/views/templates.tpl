{extends file="web/templates/backend/{$website_backendTemplate}/layout.tpl"}
{config_load file="app/backend/lang/{$website_lang}/templates.php"}

{block name = title}{#templatesManager#} &bull; {#backend#} &bull;{/block}

{block name = body}

<div class="page-header"><h3>{#templatesManager#}</h3></div>
<div class="row">
    <div class="col-lg-8">
        <table class="table table-striped">
            <thead>
            <tr>
                <th style="text-align: center;">{#preview#}</th>
                <th style="text-align: center;">{#description#}</th>
                <th style="text-align: center;">{#delete#}</th>
            </tr>
            </thead>
            <tbody>

            {foreach from = $templates item = $template}

            <tr>
                <td style="text-align: center; vertical-align: middle;"><img src="{$website_path}/{$template.preview}" style="width:400px; height:200px;" /></td>
                <td style="text-align: center; vertical-align: middle;">
                    <strong>Author:</strong> {$template.author} <br />
                    <strong>Name:</strong> {$template.name} <br />
                    <strong>Type:</strong> {$template.type} <br />
                    <strong>Description:</strong> {$template.description} <br />
                    <strong>Compatibility:</strong> {$template.compatibility} <br />
                    <strong>Dominant colors:</strong> {$template.colors} <br />
                    <strong>Framework:</strong> {$template.framework} <br />
                    <strong>HTML:</strong> {$template.html} <br />
                    <strong>CSS:</strong> {$template.css}
                </td>

                {if $template.isRemovable}

                <td style="text-align: center; vertical-align: middle;">
                    <a href="{$template.removeTemplateURL}" onclick="return confirm('{#removeTemplateConfirmation#}');" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {#delete#}</a>
                </td>

                {else}

                    <td style="text-align: center; vertical-align: middle;">
                        <a href="#" class="btn btn-sm btn-danger" disabled><span class="glyphicon glyphicon-trash"></span> {#delete#}</a>
                    </td>

                {/if}

            </tr>

            {/foreach}

            </tbody>
        </table>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">{#uploadTemplate#}</div>
            <div class="alert alert-info">
                <strong>{#info#}!</strong> {#uploadTemplateInfo#}
            </div>
            <div class="panel-body">
                <form action="{$website_path}/backend/templates/upload" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12">
                            <label>{#templateFolder#}:</label>
                            <input type="file" class="form-control" name="template" accept=".zip"">
                        </div>
                    </div>
                    <br />
                    <button type="submit" class="btn btn-primary btn-md btn-block">{#upload#}</button>
                </form>
            </div>
        </div>
    </div>
</div>

{/block}