{include file="findInclude:common/header.tpl"}

{$results = array()}
{$i = 0}

{$results[$i] = array()}
{capture name="header" assign="header"}
  {block name="header"}
    <a id="bookmark" class="{if $item['bookmarked']}bookmarked{/if}" onclick="toggleBookmark(this, '{$item['id']}', '{$item['cookie']}')"></a>
    <h2>{$item['title']}</h2>
    <br/>
    {if $item['creator']}{$item['creator']}<br/>{/if}
    {if $item['edition']}{$item['edition']}<br/>{/if}
    {if $item['date'] || $item['publisher']}{$item['date']} {$item['publisher']}<br/>{/if}
    {$item['format']|capitalize}{if strlen($item['type'])}: {$item['type']}{/if}
    {if $item['thumbnail']}
      <div class="thumbnail">
        {if $item['fullImage']}<a href="{$item['fullImage']}">{/if}
          <img src="{$item['thumbnail']}" alt="{$item['title']} thumbnail image" />
        {if $item['fullImage']}<br/><span class="smallprint">(click for full image)</span></a>{/if}
      </div>
    {/if}
  {/block}
{/capture}
{$results[$i]['title'] = $header}
{$i = $i + 1}

{foreach $locations as $location}
  {$results[$i] = array()}
  {capture name="title" assign="title"}
    {block name="locationHeader"}
      <strong>{$location['name']}</strong><br/>
      <div id="location_{$location['id']}"></div>
    {/block}
    {foreach $location['collections'] as $collection}
      {foreach $collection['items'] as $info}
        
        {if $info['available'] > 0}
          {$class = 'available'}
        {elseif $info['requestable'] > 0}
          {$class = 'requestable'}
        {else}
          {$class = 'unavailable'}
        {/if}
        {block name="item"}
          <div class="itemType {$class}">
            {$info['available']} of {$info['total']}
            {if $info['type'] != 'collection'}
              available - {$info['type']}
            {else}
              may be available
            {/if}<br/>
          </div>
        {/block}
      {/foreach}
    {/foreach}
  {/capture}
  {$results[$i]['title'] = $title}
  {$results[$i]['url'] = $location['url']}
  {$i = $i + 1} 
{/foreach}

{block name="fulllist"}
  {include file="findInclude:common/navlist.tpl" navlistItems=$results accessKey=false}
{/block}

{include file="findInclude:common/footer.tpl"}
