{extends file="../layout.tpl"}
{config_load file="app/modules/forum/lang/{$website_lang}/backend/categories.php"}

{block name = title}{#categories#} &bull; {#forum#} &bull; {#modules#} &bull; {#backend#} &bull;{/block}

{block name = module_body}

<div class="col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">{#categories#}</div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{#category#}</th>
                    <th>{#action#}</th>
                </tr>
            </thead>
            <tbody>

                {foreach from = $categories item = $category}

                <tr>
                    <td style="width:80%">{$category.name}</td>
                    <td><a href="#" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#edit_category{$category.id}">{#edit#}</a> <a href="{$website_path}/backend/modules/manage/forum/categories/remove/{$category.id}" onclick="return confirm('{#removeCategoryConfirmation#}');" class="btn btn-xs btn-danger">{#remove#}</a></td>
                </tr>

                <!-- Modal -->
                <div id="edit_category{$category.id}" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">{#editCategory#} - {$category.name}</h4>
                            </div>
                            <form class="form-horizontal" action="{$website_path}/backend/modules/manage/forum" method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <input type="hidden" class="form-control" name="category_id" value="{$category.id}">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="category_name" placeholder="{#categoryName#}" value="{$category.name}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" name="category_edit">{#edit#}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {foreachelse}

                    <tr><td colspan="4" class="text-center">{#noCategoriesCreated#}</td></tr>

                {/foreach}

            </tbody>
        </table>
    </div>
</div>
<div class="col-lg-4">
    <div class="panel panel-default">
        <div class="panel-heading">{#newCategory#}</div>
        <div class="panel-body">
            <form class="form-horizontal" action="{$website_path}/backend/modules/manage/forum" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="text" class="form-control" name="category_name" placeholder="{#categoryName#}">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-md btn-block" name="category_create">{#newCategory#}</button>
            </form>
        </div>
    </div>
</div>

{/block}