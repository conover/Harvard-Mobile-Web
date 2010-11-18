{extends file="findExtends:modules/{$moduleID}/base.tpl"}

{block name="body"}
  
  <ul class="gloss">
    <li class="search">
      <form action="/map/search/" method="get">
        <input type="search" placeholder="search" results="0" name="q"><input type="submit" value="Search" />
      </form>
    </li>
    <li class="arrow-back"><a id="back" href="/map/">View UCF Campus Map</a></li>
  </ul>
  
  
  
  
{/block}