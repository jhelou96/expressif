{config_load file="web/templates/frontend/default/lang/{$website_lang}/restrictedAccess.php"}

<!doctype html>
<title>{#restrictedAccess#}</title>
<style>
  body { text-align: center; padding: 150px; }
  h1 { font-size: 50px; }
  body { font: 20px Helvetica, sans-serif; color: #333; }
  article { display: block; text-align: left; width: 650px; margin: 0 auto; }
  a { color: #dc8100; text-decoration: none; }
  a:hover { color: #333; text-decoration: none; }
</style>

<article>
    <h1>{#accessNotAllowed#}</h1>
    <div>
        <p>{#permissionsRequired#}</p>
        <p>&mdash; <a href="{$website_path}/">{#goBack#}</a></p>
    </div>
</article>