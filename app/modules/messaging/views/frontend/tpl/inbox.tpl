{extends file="../layout.tpl"}
{config_load file="app/modules/messaging/lang/{$website_lang}/frontend/inbox.php"}

{block name=title}{#inbox#} &bull; {#messaging#} &bull; {/block}

{block name=breadcrumb}

<ol class = "breadcrumb" style="margin-top:-20px; padding-left: 50px;">
	<li><a href = "{$website_path}/">{#home#}</a><span class="arrow"></span></li>
	<li><a href = "{$website_path}/messaging">{#messaging#}</a><span class="arrow"></span></li>
	<li class = "active">{#inbox#}</li>
</ol>

{/block}

{block name=body}

<div class="row">
	<div class="box-body no-padding">
		<div class="mailbox-controls">
			<div class="pull-right">
				<a href="{$website_path}/messaging/thread/new" class="btn btn-primary">{#newMsg#}</a>
			</div>
		</div>
		
		<br /><br />
		
		<div class="table-responsive mailbox-messages">
			<table class="table table-hover">
				<tbody>
					<thead>
						<th></th>
						<th>{#createdBy#}</th>
						<th>{#subject#}</th>
						<th class="text-right">{#lastMsg#}</th>
					</thead>
					
					{foreach from = $messaging_threads item = thread}
					
					<tr {($thread.favorite || $thread.hasNewMsg) ? 'class="active"' : ''}>
						<td class="mailbox-star"><a href="{$website_path}/messaging/thread/{$thread.id}/favorite">{if $thread.favorite}<i class="fa fa-star text-yellow"></i>{else}<i class="fa fa-star-o text-yellow"></i>{/if}</a></td>
						<td class="mailbox-name"><a href="{$website_path}/members/profile/{$thread.author}">{$thread.author}</td>
						<td class="mailbox-subject"><a href="{$website_path}/messaging/thread/{$thread.id}"><b>{$thread.title}</b> - {$thread.content}</a></td>
						<td class="mailbox-date text-right">{$thread.lastMsgDate}</td>
					</tr>

					{foreachelse}

					<tr>
						<td colspan = "4" class="text-center"><i>{#noMsgsInInbox#}</i></td>
					</tr>

					{/foreach}
					
				</tbody>
			</table><!-- /.table -->
		</div><!-- /.mail-box-messages -->
	</div><!-- /.box-body -->
	
	{$pagination}
</div>

{/block}	