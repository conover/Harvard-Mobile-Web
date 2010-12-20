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
    {$subTitleNewline = false}
  {/block}
</div>

{foreach $location['collections'] as $collection}
  <div class="nonfocal">
    <h3>{$collection['name']}</h3>
  </div>
  {foreach $collection['items'] as $item}
    {capture name="itemHeader" assign="itemHeader"}
      {if $item['type'] != 'collection' || $collection['callNumber']}
        <span class="smallprint">
          {if $item['type'] != 'collection'}
            {$item['type']}
          {/if}
          {if $item['type'] != 'collection' && $collection['callNumber']}<br/>{/if}
          {if $collection['callNumber']}
            {$collection['callNumber']}
          {/if}
        </span>
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
          {block name="itemTitle"}
            <span class="itemType {$class}">
              {$info['count']}
              {if $type == 'available'}
                available
              {elseif $type == 'collection'}
                may be available
              {elseif $info['status']}
                {$info['status']}
              {else}
                {$type}
              {/if}
            </span>
            {if $info['callNumber']}
              <span class="itemType">{$info['callNumber']}</span>
            {/if}
          {/block}
        {/capture}
        {$listItem['title'] = $title}
        {capture name="subtitle" assign="subtitle"}stuff
          {block name="itemSubTitle"}
          {/block}
        {/capture}
        {$listItem['subtitle'] = $subtitle}
        {if $info['url']}
          {$listItem['url'] = $info['url']}
        {/if}
        {$list[] = $listItem}
      {/if}
    {/foreach}
    {if count($list)}
      {include file="findInclude:common/navlist.tpl" navlistItems=$list subTitleNewline=$subTitleNewline}
    {/if}
    
      <br/>
    
  {/foreach}
{/foreach}

{include file="findInclude:common/footer.tpl"}
