{include file="findInclude:common/header.tpl"}

<div class="nonfocal">
  <h2>
    <a id="infoLink" href="{$infoURL}">
      get info
    </a>
    {$location['name']}
  </h2>
  <p class="smallprint">
    {if $location['hours'] && $location['hours'] != 'closed'}
      Open today {$location['hours']}
    {else}
      Closed today
    {/if}
  </p>
</div>
  {foreach $location['items'] as $item}
<div class="nonfocal">
    <h3>{$item['typeName']|capitalize}</h3>
    <div class="smallprint">{$item['callNumber']}</div>
</div>
    
    {$list = array()}
    {foreach $item['types'] as $type => $info}
      {if $type != 'collection'}
        {$listItem = array()}
        {capture name="title" assign="title"}
          {if $type == 'available'}
            {$class = 'itemAvailable'}
          {elseif $type == 'requestable'}
            {$class = 'itemRequestable'}
          {else}
            {$class = 'itemUnavailable'}
          {/if}
          {block name="item"}
            <div class="itemType {$class}">
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
            </div>
            {if $info['callNumber']}
              <div class="itemType smallprint">{$info['callNumber']}</div>
            {/if}
          {/block}
        {/capture}
        {$listItem['title'] = $title}
        {if $info['url']}
          {$listItem['url'] = $info['url']}
        {/if}
        {$list[] = $listItem}
      {/if}
    {/foreach}
    {if count($list)}
      {include file="findInclude:common/navlist.tpl" navlistItems=$list}
    {/if}
    
    {if $item['type'] == 'collection'} 
      {if $item['types']['collection']['callNumber']}
        <div class="smallprint">{$item['types']['collection']['callNumber']}</div>
      {/if}
    {/if}
    <br/>
  {/foreach}

{include file="findInclude:common/footer.tpl"}
