{block name="bannercapture"}
    {capture name="banner" assign="banner"}
        <h1><img id="logo" src="/modules/home/images/logo-home.png" width="400" height="67" alt="{$SITE_NAME}" /></h1>
    {/capture}
{/block}

{block name="header"}
    {include file="findInclude:common/header-compliant-bbplus.tpl" customHeader=$banner scalable=false}
{/block}

{block name="homeSearch"}
  {include file="findInclude:common/search-compliant-bbplus.tpl" placeholder="Search "|cat:$SITE_NAME}
{/block}

{block name="moduleItems"}
    {if $home['springboard']}
      {include file="findInclude:common/springboard.tpl" springboardItems=$modules springboardID="homegrid"}
    {else}
      {include file="findInclude:common/navlist.tpl" navlistItems=$modules}
    {/if}
{/block}

{block name="homeFooter"}
  <div id="download">
    <a href="../download/">
      <img src="/modules/home/images/download-bbplus.png" width="32" height="26" alt="Download" align="absmiddle" />
      Add the BlackBerry shortcut to your home screen
    </a>
    <br />
  </div>
{/block}

{include file="findInclude:common/footer.tpl"}

