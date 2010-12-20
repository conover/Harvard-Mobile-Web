{include file="findInclude:common/header.tpl"}

<div class="nonfocal">
  {block name="header"}
    <h2>
      <a id="infoLink" href="{$infoURL}">
        <img class="infoLink" src="/common/images/action-search.png" alt="get info" />
      </a>
      {$location['name']}
    </h2>
    <span class="smallprint">
      {if $location['hours'] && $location['hours'] != 'closed'}
        Open today {$location['hours']}
      {else}
        Closed today
      {/if}
    </span><br/>
  {/block}
</div>

{foreach $location['items'] as $item}
  {capture name="itemHeader" assign="itemHeader"}
    <h3>{$item['typeName']|capitalize}</h3>
    {if $item['type'] == 'collection' && $item['types']['collection']['callNumber']}
      <span class="smallprint">{$item['types']['collection']['callNumber']}</span>
    {elseif $item['callNumber']}
      <span class="smallprint">{$item['callNumber']}</span>
    {/if}
  {/capture}
  
  <div class="nonfocal">
    {$itemHeader}
  </div>
  
  {$list = array()}
  {foreach $item['types'] as $type => $info}
    {if $type != 'collection'}
      {$listItem = array()}
      {capture name="title" assign="title"}
        {if $type == 'available'}
          {$class = 'available'}
        {elseif $type == 'requestable'}
          {$class = 'requestable'}
        {else}
          {$class = 'unavailable'}
        {/if}
        {block name="item"}
          <span class="itemType {$class}">
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
          </span>
        {/block}
      {/capture}
      {$listItem['title'] = $title}
      {capture name="subtitle" assign="subtitle"}
        {if $info['callNumber']}
          <span class="itemType">{$info['callNumber']}</span>
        {/if}
      {/capture}
      {$listItem['subtitle'] = $subtitle}
      {if $info['url']}
        {$listItem['url'] = $info['url']}
      {/if}
      {$list[] = $listItem}
    {/if}
  {/foreach}
  {if count($list)}
    {include file="findInclude:common/navlist.tpl" navlistItems=$list}
  {/if}
  
    <br/>
  
{/foreach}

{include file="findInclude:common/footer.tpl"}
