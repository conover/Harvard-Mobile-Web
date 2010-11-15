{include file="findInclude:common/header.tpl" customHeader=$banner scalable=false}

{*
{block name="homeSearch"}
  {include file="findInclude:common/search.tpl" placeholder="Search "|cat:$SITE_NAME}  
{/block}
*}

{if $home['springboard']}
  {include file="findInclude:common/springboard.tpl" springboardItems=$modules springboardID="homegrid"}
{else}
  {* include file="findInclude:common/navlist.tpl" navlistItems=$modules *}
  
  
  <ul class="gloss">
  
  {foreach $modules as $item}     
    <li class="Application arrow"><a href="{$item['url']}" class="{$item['class']|default:''}">{$item['title']}</a></li>
  {/foreach}
  
  </ul>
  
  
{/if}


{block name="homeFooter"}{/block}

{include file="findInclude:common/footer.tpl"}
