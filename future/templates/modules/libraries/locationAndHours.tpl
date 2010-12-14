{include file="findInclude:common/header.tpl"}

<div class="nonfocal">
  <a id="bookmark" class="{if $item['bookmarked']}bookmarked{/if}" onclick="toggleBookmark(this, '{$item['id']}', '{$item['cookie']}')"></a>
  <h2>{$item['name']}</h2>
</div>

{foreach $item['infoSections'] as $key => $section}
  {$entries = array()}
  {foreach $section as $entry}
    {capture name="title" assign="title"}
      <div class="label">{$entry['label']}</div>
      <div class="value">{$entry['title']}</div>
    {/capture}
    {$entry['title'] = $title}
    {$entry['label'] = null}
    {$entries[] = $entry}
  {/foreach}
  {include file="findInclude:common/navlist.tpl" navlistItems=$entries accessKey=false labelColon=false}
{/foreach}


{include file="findInclude:common/footer.tpl"}
