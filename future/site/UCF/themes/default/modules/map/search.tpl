{extends file="findExtends:common/base.tpl"}

{block name="body"}
    <ul class="gloss">
      <li class="search">
        <form action="../search/" method="get">
          <input type="search" placeholder="search" results="0" name="q" value="{$search_q}"><input type="submit" value="Search" />
        </form>
      </li>
  
  {if empty($results)}
    </ul>
    <p id="no-results">No results</p>
  {else}
      {foreach $results as $item}
      <li class="arrow"><a href="/map/location/{$item->number}">{$item->name}</a></li>
      {/foreach}
    </ul>
  {/if}
  
    <ul class="gloss">
      <li class="arrow-back"><a href="/map/options/">Back to Options</a></li>
      <li class="arrow-back"><a href="/map/">View UCF Campus Map</a></li>
    </ul>

{/block}