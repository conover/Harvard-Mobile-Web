{include file="findInclude:common/header.tpl"}

{$results = array()}
{$i = 0}

{$results[$i] = array()}
{capture name="header" assign="header"}
  {block name="header"}
  {/block}
{/capture}
{$results[$i]['title'] = $header}
{$i = $i + 1}

{if $item['isOnline']}
  {$results[$i] = array()}
  {$results[$i]['title'] = '<strong>Available Online</strong>'}
  {$results[$i]['class'] = 'external'}
  {$results[$i]['linkTarget'] = 'new'}
  {$results[$i]['url'] = $item['onlineUrl']}
  {$i = $i + 1}
{/if}

{foreach $locations as $location}
  {$results[$i] = array()}
  {capture name="title" assign="title"}
    {block name="locationHeader"}
      <strong>{$location['name']}</strong><br/>
      <div class="distance" id="location_{$location['id']}"></div>
    {/block}
    {foreach $location['categories'] as $category}
      
      {if $category['available'] > 0}
        {$class = 'available'}
      {elseif $category['requestable'] > 0}
        {$class = 'requestable'}
      {else}
        {$class = 'unavailable'}
      {/if}
      {capture name="itemText" assign="itemText"}
        {if $category['collection'] > 0}
          {$category['collection']} may be available
        {else}
          {$category['available']} of {$category['total']} available - {$category['holdingStatus']}
        {/if}
      {/capture}
      {block name="item"}
        <div class="itemType {$class}">
          {$itemText}
        </div>
      {/block}
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
