{extends file="../layout.tpl"}
{config_load file="app/modules/articles/lang/{$website_lang}/backend/articles.php"}

{block name = title}{#articles#} &bull; {#modules#} &bull; {#backend#} &bull;{/block}

{block name = module_body}

<div class="col-lg-10">
    <div class="panel panel-default">
        <div class="panel-heading">{#articles#}</div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{#title#}</th>
                    <th>{#description#}</th>
                    <th>{#category#}</th>
                    <th>{#publicationDate#}</th>
                    <th>{#action#}</th>
                </tr>
            </thead>
            <tbody>

                {foreach from = $articles item = $article}

                <tr>
                    <td>{$article.title}</td>
                    <td>{$article.description}</td>
                    <td>{$article.category}</td>
                    <td>{$article.publicationDate}</td>
                    <td><a href="{$article.articleURL}" class="btn btn-xs btn-info">{#see#}</a> <a href="{$website_path}/backend/modules/manage/articles/edit/{$article.id}" class="btn btn-xs btn-primary">{#moderate#}</a></td>
                </tr>

                {foreachelse}

                    <tr><td colspan="4" class="text-center">{#noArticlesPublishedYet#}</td></tr>

                {/foreach}

            </tbody>
        </table>
    </div>
    {$pagination}
</div>

{/block}