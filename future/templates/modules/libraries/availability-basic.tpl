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
  </span><br/><br/>
{/block}

{block name="item"}
  <img src="/modules/{$moduleID}/images/{$class}.gif" alt="" />
  {$info['count']}
  {if $type == 'available'}
    available
  {elseif $type == 'collection'}
    restricted
  {elseif $info['status']}
    {$info['status']}
  {else}
    {$type}
  {/if}
  <br/>
  {if $info['callNumber']}
    <span class="smallprint">{$info['callNumber']}</span>
  {/if}
{/block}

{block name="itemHeader"}
  <div class="nonfocal">
    {$itemHeader}
{/block}

{block name="itemFooter"}
  </div><br/>
{/block}
