{extends file="findExtends:modules/{$moduleID}/availability.tpl"}

{block name="header"}
  <h2>
    {$location['name']}
  </h2>
  <span class="smallprint">
    {if $location['hours'] && $location['hours'] != 'closed'}
      Open today {$location['hours']}
    {else}
      Closed today
    {/if}
    (<a id="infoLink" href="{$infoURL}">get info</a>)
  </span><br/>
{/block}

{block name="itemTitle"}
  <img src="/modules/{$moduleID}/images/{$class}.gif" alt="" />
  {$item['count']}
  {if $item['status'] == 'collection'}may be available{else}{$item['status']}{/if}
  {if $item['secondary']}({$item['secondary']}){/if}<br/>
  <span class="smallprint">
    {if $item['callNumber']}{$item['callNumber']}{if $item['description']}, {/if}{/if}
    {if $item['description']}{$item['description']}{/if}
  </span>
{/block}
