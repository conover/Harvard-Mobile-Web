{extends file="findExtends:modules/{$moduleID}/index.tpl"}

{block name="banner"}
  <h1{if isset($topItem)} class="roomfornew"{/if}>
    <img src="/modules/home/images/logo-home.png" width="210" height="35" alt="{$SITE_NAME}" />
  </h1>

  {if isset($topItem)}
      <div id="new"><a href="/about/new.php"><span class="newlabel">NEW:</span>{$topItem}</a></div>
  {/if}
{/block}

{block name="homeSearch"}
  {include file="findInclude:common/search-compliant-blackberry.tpl" placeholder="Search "|cat:$SITE_NAME}  
{/block}

{block name="moduleItems"}
<div id="outerSpringboardContainer">
    {include file="findInclude:common/springboard-compliant-blackberry.tpl" 
    springboardItems=$modules springboardID="homegrid"}
</div>
{/block}

{block name="homeFooter"}
    <br /><br />
    <div class="separator"></div>
    <br /><br />

    <div id="download">
        <a href="../download/">
          <img src="/modules/home/images/download-bbplus.png" width="32" height="26" alt="Download" align="absmiddle" />
          Add the BlackBerry shortcut to your home screen.
        </a>
        <br />
    </div>
{/block}
