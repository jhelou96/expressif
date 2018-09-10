{extends file="../layout.tpl"}
{config_load file="app/modules/forum/lang/{$website_lang}/backend/subCategories.php"}

{block name = title}{#subCategories#} &bull; {#forum#} &bull; {#modules#} &bull; {#backend#} &bull;{/block}

{block name = module_body}

<div class="col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">{#subCategories#}</div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{#subCategory#}</th>
                    <th>{#description#}</th>
                    <th>{#action#}</th>
                </tr>
            </thead>
            <tbody>

                {foreach from = $subCategories item = $subCategory}

                <tr>
                    <td style="width:20%">{$subCategory.name}</td>
                    <td style="width:60%">{$subCategory.description}</td>
                    <td><a href="#" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#edit_subCategory{$subCategory.id}">{#edit#}</a> <a href="{$website_path}/backend/modules/manage/forum/subcategories/remove/{$subCategory.id}" onclick="return confirm('{#removeSubCategoryConfirmation#}');" class="btn btn-xs btn-danger">{#remove#}</a></td>
                </tr>

                <!-- Modal -->
                <div id="edit_subCategory{$subCategory.id}" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">{#editSubCategory#} - {$subCategory.name}</h4>
                            </div>
                            <form class="form-horizontal" action="{$website_path}/backend/modules/manage/forum/subcategories" method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <input type="hidden" class="form-control" name="subCategory_id" value="{$subCategory.id}">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="subcategory_name" placeholder="{#subCategoryName#}" value="{$subCategory.name}">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="subcategory_description" placeholder="{#subCategoryDescription#}" value="{$subCategory.description}">
                                            </div>
                                            <div class="form-group">
                                                <label for="subcategory_idCategory">{#subCategoryParent#}:</label>
                                                <select class="form-control" id="subcategory_idCategory" name="subcategory_idCategory">

                                                    {foreach from = $categories item = $category}

                                                        <option value="{$category.id}" {($subCategory.idCategory == $category.id) ? 'selected' : ''}>{$category.name}</option>

                                                    {/foreach}

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" name="subcategory_edit">{#edit#}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {foreachelse}

                    <tr><td colspan="4" class="text-center">{#noSubCategoriesCreated#}</td></tr>

                {/foreach}

            </tbody>
        </table>
    </div>
</div>
<div class="col-lg-4">
    <div class="panel panel-default">
        <div class="panel-heading">{#newSubCategory#}</div>
        <div class="panel-body">
            <form class="form-horizontal" action="{$website_path}/backend/modules/manage/forum/subcategories" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="text" class="form-control" name="subcategory_name" placeholder="{#subCategoryName#}" {if isset($subcategory_name)} value="{$subcategory_name}" {/if}>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="subcategory_description" placeholder="{#subCategoryDescription#}" {if isset($subcategory_description)} value="{$subcategory_description} {/if}">
                        </div>
                        <div class="form-group">
                            <label for="subcategory_idCategory">{#subCategoryParent#}:</label>
                            <select class="form-control" id="subcategory_idCategory" name="subcategory_idCategory">

                                {foreach from = $categories item = $category}

                                <option value="{$category.id}" {(isset($subcategory_idCategory) AND $subcategory_idCategory == $category.id) ? 'selected' : ''}>{$category.name}</option>

                                {/foreach}

                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-md btn-block" name="subcategory_create">{#newSubCategory#}</button>
            </form>
        </div>
    </div>
</div>

{/block}