{extends file="web/templates/backend/{$website_backendTemplate}/layout.tpl"}
{config_load file="app/backend/lang/{$website_lang}/configuration.php"}

{block name = title}{#configuration#} &bull; {#backend#} &bull;{/block}

{block name = body}

<div class="page-header"><h3>{#configuration#}</h3></div>
<div class="row">
    <div class="col-lg-12">
        <form action="{$website_path}/backend/configuration" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="website_name" placeholder="{#websiteName#}" value="{$website_name}">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="website_url" placeholder="{#serverURL#}" value="{$website_url}">
            </div>
			 <div class="form-group">
                <input type="email" class="form-control" name="website_email" placeholder="{#webmasterEmail#}" value="{$website_email}">
				<p class="help-block">{#webmasterEmailUse#}</p>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="website_path" placeholder="{#cmsPath#}" value="{$website_path}">
            </div>
            <div class="form-group">
                <label for="website_lang">{#defaultLang#}</label>
                <select class="form-control" name="website_lang">
                    <option value="fr" {($website_lang == 'fr') ? 'selected' : ''}>Français</option>
                    <option value="en" {($website_lang == 'en') ? 'selected' : ''}>English</option>
                </select>
            </div>
            <div class="form-group">
                <label for="website_frontTheme">{#frontendTheme#}</label>
                <select class="form-control" name="website_frontTheme">

                    {foreach from = $frontThemes item = $theme}

                    <option value="{$theme}" {($website_frontTheme == $theme) ? 'selected' : ''}>{$theme}</option>

                    {/foreach}

                </select>
            </div>
            <div class="form-group">
                <label for="website_backTheme">{#backendTheme#}</label>
                <select class="form-control" name="website_backTheme">

                    {foreach from = $backThemes item = $theme}

                        <option value="{$theme}" {($website_backTheme == $theme) ? 'selected' : ''}>{$theme}</option>

                    {/foreach}

                </select>
            </div>
            <div class="form-group">
                <label for="website_timezone">{#timezone#}</label>
                <select class="form-control" name="website_timezone">
                    {foreach from = $timezones key = $region item = $list}

                        <optgroup label="{$region}">

                            {foreach from = $list key = $timezone item = $name}

                                <option value="{$timezone}" {($timezone == $website_timezone) ? 'selected' : ''}>{$name}</option>

                            {/foreach}

                        </optgroup>

                    {/foreach}
                </select>
            </div>
            <div class="checkbox checkbox-primary">
                <input type="checkbox" name="website_maintenance" {($website_maintenance eq '1') ? 'checked' : ''}>
                <label for="website_maintenance">
                    {#websiteMaintenance#}
                    <p>{#websiteMaintenanceWarning#}</p>
                </label>
            </div>
            <button type="submit" class="btn btn-primary pull-right">{#submit#}</button>
        </form>
    </div>
</div>

{/block}