{extends file="web/templates/backend/{$website_backendTemplate}/layout.tpl"}
{config_load file="app/backend/lang/{$website_lang}/installModule.php"}

{block name = title}{#moduleInstaller#} &bull; {#modulesManager#} &bull; {#backend#} &bull;{/block}

{block name = body}

<div class="page-header"><h3>{#modulesManager#}</h3></div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">{#moduleInstallerTool#}</div>
            <div class="alert alert-info">
                <strong>{#info#}!</strong> {#uploadModuleInfo#} <u>{#ftpUploadShouldBeAvoided#}</u>
            </div>
            <div class="panel-body">
                <form action="{$website_path}/backend/modules/install" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12">
                            <label>{#moduleFolder#}:</label>
                            <input type="file" class="form-control" name="module" accept=".zip"">
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