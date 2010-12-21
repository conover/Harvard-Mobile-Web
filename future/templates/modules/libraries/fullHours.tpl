{include file="findInclude:common/header.tpl"}

{capture name="libraryName" assign="libraryName"}
  <h2>{$item['name']}
    {if $item['fullName'] && $item['fullName'] != $item['name']}
      <br/>({$item['fullName']})</p>
    {/if}
  </h2>
{/capture}

<div class="nonfocal libraryName">
  {block name="header"}
    <a id="bookmark" class="{if $item['bookmarked']}bookmarked{/if}" onclick="toggleBookmark(this, '{$item['id']}', '{$item['cookie']}')"></a>
    {$libraryName}
  {/block}
</div>

{foreach $item['hours'] as $entry}
  {block name="item"}
    {capture name="title" assign="title"}
      {if $entry['label']}
        <span class="label">{$entry['label']}<br/></span>
      {/if}
      <span class="value">{$entry['title']}</span>
    {/capture}
    {$item['hours'][$entry@index]['title'] = $title}
    {$item['hours'][$entry@index]['label'] = null}
  {/block}
{/foreach}
{block name="itemList"}
  {include file="findInclude:common/navlist.tpl" navlistItems=$item['hours'] accessKey=false labelColon=false}
{/block}

{include file="findInclude:common/footer.tpl"}
