{extends file="../layout.tpl"}
{config_load file="app/modules/members/lang/{$website_lang}/backend/members.php"}

{block name = title}{#members#} &bull; {#modules#} &bull; {#backend#} &bull;{/block}

{block name = module_body}

<div class="col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">{#members#}</div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{#username#}</th>
                    <th>{#email#}</th>
                    <th>{#level#}</th>
                    <th>{#action#}</th>
                </tr>
            </thead>
            <tbody>

            {foreach from = $members item = $member}

                <tr>
                    <td style="width:30%">{$member.username}</td>
                    <td style="width:35%">{$member.email}</td>
                    <td style="width:5%">

                        {if $member.level eq -1}
                        <span class="label label-default">{#banned#}</span>
                        {elseif $member.level eq 0}
                        <span class="label label-warning">{#notConfirmed#}</span>
                        {elseif $member.level eq 1}
                        <span class="label label-primary">{#member#}</span>
                        {elseif $member.level eq 2}
                        <span class="label label-success">{#moderator#}</span>
                        {elseif $member.level eq 3}
                        <span class="label label-danger">{#administrator#}</span>
                        {/if}

                    </td>
                    <td style="width:30%">

                        {if $isAdministrator}
                        <a href="#" class="btn btn-xs btn-info" data-toggle="modal" data-target="#change_group_member{$member.id}">{#changeGroup#}</a>
                        {else}
                        <a href="#" class="btn btn-xs btn-info" disabled>{#changeGroup#}</a>
                        {/if}

                        {if $member.level != -1}
                        <a href="{$website_path}/backend/modules/manage/members/{$member.id}/ban" onclick="return confirm('{#banMemberConfirmation#}');" class="btn btn-xs btn-danger">{#banMember#}</a>
                        {else}
                        <a href="{$website_path}/backend/modules/manage/members/{$member.id}/unban" onclick="return confirm('{#unbanMemberConfirmation#}');" class="btn btn-xs btn-warning">{#unbanMember#}</a>
                        {/if}

                    </td>
                </tr>

                <!-- Modal -->
                <div id="change_group_member{$member.id}" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">{#changeGroup#} - {$member.username}</h4>
                            </div>
                            <form class="form-horizontal" action="{$website_path}/backend/modules/manage/members/{$member.id}/change-group" method="post">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="member_level">{#group#}:</label>
                                        <select class="form-control" id="member_level" name="member_level">
                                            <option value="1" {($member.level == 1) ? 'selected' : ''}>{#members#}</option>
                                            <option value="2" {($member.level == 2) ? 'selected' : ''}>{#moderators#}</option>
                                            <option value="3" {($member.level == 3) ? 'selected' : ''}>{#administrators#}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">{#changeGroup#}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            {/foreach}

            </tbody>
        </table>
    </div>
    {$pagination}
</div>
<div class="col-lg-4">
    <div class="panel panel-default">
        <div class="panel-heading">{#searchMember#}</div>
        <div class="panel-body">
            <form class="form-horizontal" action="{$website_path}/backend/modules/manage/members" method="post">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="text" class="form-control" name="members_search" placeholder="{#searchField#}">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-md btn-block">{#search#}</button>
            </form>
        </div>

        {if isset($search_results)}

        <div class="panel-footer">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>{#username#}</th>
                    <th>{#email#}</th>
                    <th>{#level#}</th>
                    <th>{#action#}</th>
                </tr>
                </thead>
                <tbody>

                {foreach from = $search_results item = $member}

                    <tr>
                        <td style="width:30%">{$member.username}</td>
                        <td style="width:35%">{$member.email}</td>
                        <td style="width:10%">

                            {if $member.level eq -1}
                                <span class="label label-default">{#banned#}</span>
                            {elseif $member.level eq 0}
                                <span class="label label-warning">{#notConfirmed#}</span>
                            {elseif $member.level eq 1}
                                <span class="label label-primary">{#member#}</span>
                            {elseif $member.level eq 2}
                                <span class="label label-success">{#moderator#}</span>
                            {elseif $member.level eq 3}
                                <span class="label label-danger">{#administrator#}</span>
                            {/if}

                        </td>
                        <td style="width:25%">

                            {if $isAdministrator}
                                <a href="#" class="btn btn-xs btn-info" data-toggle="modal" data-target="#change_group_member{$member.id}">{#changeGroup#}</a>
                            {else}
                                <a href="#" class="btn btn-xs btn-info" disabled>{#changeGroup#}</a>
                            {/if}

                            {if $member.level != -1}
                                <a href="{$website_path}/backend/modules/manage/members/{$member.id}/ban" onclick="return confirm('{#banMemberConfirmation#}');" class="btn btn-xs btn-danger">{#banMember#}</a>
                            {else}
                                <a href="{$website_path}/backend/modules/manage/members/{$member.id}/unban" onclick="return confirm('{#unbanMemberConfirmation#}');" class="btn btn-xs btn-warning">{#unbanMember#}</a>
                            {/if}
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div id="change_group_member{$member.id}" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">{#changeGroup#} - {$member.username}</h4>
                                </div>
                                <form class="form-horizontal" action="{$website_path}/backend/modules/manage/members/{$member.id}/change-group" method="post">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="member_level">{#group#}:</label>
                                            <select class="form-control" id="member_level" name="member_level">
                                                    <option value="1" {($member.level == 1) ? 'selected' : ''}>{#members#}</option>
                                                    <option value="2" {($member.level == 2) ? 'selected' : ''}>{#moderators#}</option>
                                                    <option value="3" {($member.level == 3) ? 'selected' : ''}>{#administrators#}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">{#changeGroup#}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {foreachelse}

                    <td colspan="4"><p class="text-center"><i>{#noResultsFound#}</i></p></td>

                    {/foreach}

                </tbody>
            </table>

        </div>

        {/if}

    </div>
</div>

{/block}