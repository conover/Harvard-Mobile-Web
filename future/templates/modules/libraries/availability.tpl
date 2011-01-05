{include file="findInclude:common/header.tpl"}

<div class="nonfocal">
  {block name="header"}
    <h2>
      <a id="infoLink" href="{$infoURL}">
        <img class="infoLink" src="/common/images/info_button@2x.png" alt="get info" width="44" height="38" />
      </a>
      {$location['name']}
      {if $location['primaryname'] != $location['name']}<br/>({$location['primaryname']}){/if}
    </h2>
    <span class="distance">
      {if $location['hours'] && $location['hours'] != 'closed'}
        Open today {$location['hours']}
      {else}
        Closed today
      {/if}
    </span><br/>
  {/block}
</div>

{foreach $location['collections'] as $collection}
  <div class="nonfocal">
    <h3>{$collection['name']}</h3>
  {foreach $collection['types'] as $type}
    {if !$type@first}<div class="nonfocal">{/if}
      {if $item['type'] != 'collection' || $collection['callNumber']}
        <span class="smallprint">
          {if $type['type'] != 'collection'}{$type['type']}{/if}
          {if $type['type'] != 'collection' && $collection['callNumber']}<br/>{/if}
          {if $collection['callNumber']}{$collection['callNumber']}{/if}
        </span>
      {/if}
    </div>
    
    {$list = array()}
    {foreach $type['items'] as $item}
      {if $type['type'] != 'collection'}
        {$listItem = array()}
        {capture name="title" assign="title"}
          {if $item['status'] == 'available'}
            {$class = 'available'}
          {elseif $item['status'] == 'requestable'}
            {$class = 'requestable'}
          {else}
            {$class = 'unavailable'}
          {/if}
          {block name="itemTitle"}
            <span class="itemType {$class}">
              {$item['count']}
              {if $item['status'] == 'collection'}may be available{else}{$item['status']}{/if}
              {if $item['secondary']}({$item['secondary']}){/if}
            </span>
            <span class="itemType"><span class="smallprint">
              {if $item['callNumber']}{$item['callNumber']}{if $item['description']}, {/if}{/if}
              {if $item['description']}{$item['description']}{/if}
            </span></span>
          {/block}
        {/capture}
        {$listItem['title'] = $title}
        {if $item['url']}
          {$listItem['url'] = $item['url']}
        {/if}
        {$list[] = $listItem}
      {/if}
    {/foreach}
    {if count($list)}
      <div class="items">
        {include file="findInclude:common/navlist.tpl" navlistItems=$list subTitleNewline=false accessKey=false}
      </div>
    {/if}
  {/foreach}
{/foreach}

{include file="findInclude:common/footer.tpl"}
