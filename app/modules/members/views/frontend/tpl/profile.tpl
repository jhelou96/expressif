{extends file="../layout.tpl"}
{config_load file="app/modules/members/lang/{$website_lang}/frontend/profile.php"}

{block name=title}{#profile#}: {$member_username} &bull; {#members#} &bull; {/block}		

{block name=breadcrumb}

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
	<li><a href = "#">{#members#}</a><span class="arrow"></span></li>
	<li class = "active">{#profile#}: {$member_username}</li>
</ol>

{/block}

{block name=body}
<div class="row">
	<div class="col-lg-4">
		<div class="panel panel-default">
			<div class="panel-heading">{#profile#}: {$member_username}</div>
				<ul class="list-group">
					<li class="list-group-item">
						<p class="text-center">
							<img class="img-circle" src="{$website_path}/{$member_avatar}" style="width: 80px; height: 80px;" /></i> <br /><br />
							{if $member_isConnected} <img src="{$website_path}/app/modules/members/views/frontend/images/online.png" style="width: 16px; height: 16px;" /> <i>{$member_username} {#isConnected#}</i> {/if}
						</p>
					</li>
					<li class="list-group-item">
						<strong>{#status#}:</strong> <span class="pull-right">{if $member_level == -1} <span class="label label-default">{#banned#}</span> {elseif $member_level == 0 OR $member_level == 1} <span class="label label-primary">{#member#}</span> {elseif $member_level == 2} <span class="label label-success">{#moderator#}</span> {elseif $member_level == 3} <span class="label label-danger">{#administrator#}</span> {/if}</span>
					</li>
					<li class="list-group-item">
						<strong>{#registrationDate#}:</strong> <span class="pull-right">{$member_registrationDate}</span>
					</li>
					<li class="list-group-item">
						<strong>{#lastConnectionDate#}:</strong> <span class="pull-right">{$member_lastConnectionDate}</span>
					</li>

					{if $forum_isModuleActive}

					<li class="list-group-item">
						<strong>{#nbForumTopicsCreated#}:</strong> <span class="pull-right">{$forum_memberNbTopicsCreated}</span>
					</li>
					<li class="list-group-item">
						<strong>{#nbMessagesPosted#}:</strong> <span class="pull-right">{$forum_memberNbMessagesPosted}</span>
					</li>

					{/if}

					{if $articles_isModuleActive}

					<li class="list-group-item">
						<strong>{#nbArticlesPublished#}:</strong> <span class="pull-right">{$articles_memberNbArticlesPublished}</span>
					</li>

					{/if}

					{if !empty($member_signature)}
					<li class="list-group-item text-center">
						<i>"{$member_signature}"</i>
					</li>
					{/if}
				</ul>

				{if !$member_isMyProfile AND $isConnected}

				<div class="panel-footer">

					{if $messaging_isModuleActive}

					<span class="text-left"><a href="{$website_path}/messaging/thread/new/{$member_username}" class="btn btn-default stat-item" data-toggle="tooltip" data-placement="right" title="{#sendMsg#}"><i class="glyphicon glyphicon-envelope"></i></a></span>

					{/if}

					<span class="text-right">

						{if $isModerator}
						{if $member_level == -1}

						<a href="{$website_path}/backend/modules/manage/members/{$member_id}/unban" class="btn btn-default stat-item pull-right" data-toggle="tooltip" data-placement="left" title="{#unbanMember#}" onclick="return confirm('{#unbanConfirmation#}');">
							<i class="glyphicon glyphicon-warning-sign"></i>
						</a>

						{else}

						<a href="{$website_path}/backend/modules/manage/members/{$member_id}/ban" class="btn btn-default stat-item pull-right" data-toggle="tooltip" data-placement="left" title="{#banMember#}" onclick="return confirm('{#banConfirmation#}');">
							<i class="glyphicon glyphicon-warning-sign"></i>
						</a>

						{/if}
						{/if}

					</span>
				</div>

				{/if}

			</div>
		</div>
	<div class="col-lg-8">
		<div class="panel panel-default">
			<div class="panel-heading">{#achievements#}</div>
			<div class="panel-body" style="overflow-y:scroll; height: 400px;" >
				<p class="text-center">
					{foreach from = $member_achievements item = achievement}
					
					<img src="{$achievement.thumbnail}" style="width: 100px; height: 100px;" data-toggle="tooltip" data-placement="bottom" title="{$achievement.description}" />
				
					{/foreach}
				</p>
			</div>
		</div>
	</div>
</div>
<div class="row">

	{if $forum_isModuleActive}

	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">{#recentForumActivity#}</div>
				<ul class="list-group">
				
					{foreach from = $forum_memberParticipation item = topic}
				
					<li class="list-group-item">
						<div class="row" style="height: 100%; display: table-row;">
								<h4>
									<a href="{$topic.topicURL}">{$topic.title}</a> <br /> 
									<small><strong>{#category#}:</strong> {$topic.subCategory} - {#createdBy#} <a href="#">{$topic.author}</a> - {$topic.creationDate}</small><br />
									<small><i>{$member_username} {#postedMessageOn#} {$topic.lastMsgPostedByMemberDate}</i></small>
								</h4>
						</div>
					</li>

					{foreachelse}

						<li class="list-group-item text-center"><i>{$member_username} {#noForumActivity#}</i></li>

					{/foreach}
					
				</ul>
		</div>
	</div>

	{/if}
	{if $articles_isModuleActive}

	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">{#latestArticlesPublished#}</div>
			<ul class="list-group">

                {foreach from = $articles_memberLatestArticlesPublished item = article}

					<li class="list-group-item">
						<div class="row" style="height: 100%; display: table-row;">
							<h4>
								<a href="{$article.url}">{$article.title}</a> <br />
								<small>{$article.description}</small><br />
								<small><strong>{#category#}:</strong> {$article.category} - {#publishedOn#} {$article.publicationDate}</small><br />
							</h4>
						</div>
					</li>

                    {foreachelse}

					<li class="list-group-item text-center"><i>{$member_username} {#noPublishedArticles#}</i></li>

                {/foreach}

			</ul>
		</div>
	</div>

	{/if}

</div>
{/block}