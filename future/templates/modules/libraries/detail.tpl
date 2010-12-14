{include file="findInclude:common/header.tpl"}

{$results = array()}
{$i = 0}

{$results[$i] = array()}
{capture name="header" assign="header"}
  <a id="bookmark" class="{if $item['bookmarked']}bookmarked{/if}" onclick="toggleBookmark(this, '{$item['id']}', '{$item['cookie']}')"></a>
  <h2>{$item['title']}</h2>
  <p><br/>
    {$item['creator']}<br/>
    {if $item['edition']}{$item['edition']}<br/>{/if}
    {$item['date']}<br/>
    {$item['format']|capitalize}: {$item['type']}
  </p>
{/capture}
{$results[$i]['title'] = $header}
{$i = $i + 1}

{foreach $libraries as $library}
  {$results[$i] = array()}
  {capture name="header" assign="header"}
    <strong>{$library['name']}</strong>
    
  {/capture}
  {$results[$i]['title'] = $title}
  {$i = $i + 1} 
{/foreach}

{include file="findInclude:common/navlist.tpl" navlistItems=$results accessKey=false}

{include file="findInclude:common/footer.tpl"}
