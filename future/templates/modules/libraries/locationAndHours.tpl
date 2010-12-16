{include file="findInclude:common/header.tpl"}

<div class="nonfocal">
  {block name="header"}
    <a id="bookmark" class="{if $item['bookmarked']}bookmarked{/if}" onclick="toggleBookmark(this, '{$item['id']}', '{$item['cookie']}')"></a>
    <h2>{$item['name']}</h2>
  {/block}
</div>

{foreach $item['infoSections'] as $key => $section}
  {$sectionIsLast = $section@last}
  {$entries = array()}
  {foreach $section as $entry}
    {$itemIsLast = $entry@last}
    {block name="item"}
      {capture name="title" assign="title"}
        {if $entry['label']}
          <span class="label">{$entry['label']}<br/></span>
        {/if}
        <span class="value">{$entry['title']}</span>
      {/capture}
      {$section[$entry@index]['title'] = $title}
      {$section[$entry@index]['label'] = null}
    {/block}
  {/foreach}
  {block name="itemList"}
    {include file="findInclude:common/navlist.tpl" navlistItems=$section accessKey=false labelColon=false}
  {/block}
{/foreach}


{include file="findInclude:common/footer.tpl"}
