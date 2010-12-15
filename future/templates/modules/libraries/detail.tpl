{include file="findInclude:common/header.tpl"}

{$results = array()}
{$i = 0}

{$results[$i] = array()}
{capture name="header" assign="header"}
  {block name="header"}
    <a id="bookmark" class="{if $item['bookmarked']}bookmarked{/if}" onclick="toggleBookmark(this, '{$item['id']}', '{$item['cookie']}')"></a>
    <h2>{$item['title']}</h2>
    <br/>
    {$item['creator']}<br/>
    {if $item['edition']}{$item['edition']}<br/>{/if}
    {$item['date']}<br/>
    {$item['format']|capitalize}{if strlen($item['type'])}: {$item['type']}{/if}
  {/block}
{/capture}
{$results[$i]['title'] = $header}
{$i = $i + 1}

{foreach $locations as $location}
  {$results[$i] = array()}
  {capture name="title" assign="title"}
    <strong>{$location['name']}</strong><br/>
    <div id="location_{$location@index}"></div>
    {foreach $location['items'] as $info}
      
      {if $info['available'] > 0}
        {$class = 'itemAvailable'}
      {elseif $info['requestable'] > 0}
        {$class = 'itemRequestable'}
      {else}
        {$class = 'itemUnavailable'}
      {/if}
      {block name="item"}
        <div class="itemType {$class}">
          {$info['available']} of {$info['total']}
          {if $info['type'] != collection}available - {$info['type']}{else}restricted{/if}<br/>
        </div>
      {/block}
    {/foreach}
  {/capture}
  {$results[$i]['title'] = $title}
  {$results[$i]['url'] = $location['url']}
  {$i = $i + 1} 
{/foreach}

{include file="findInclude:common/navlist.tpl" navlistItems=$results accessKey=false}

{include file="findInclude:common/footer.tpl"}
