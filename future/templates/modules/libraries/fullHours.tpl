{include file="findInclude:common/header.tpl"}

<div class="nonfocal">
  <a id="bookmark" class="{if $item['bookmarked']}bookmarked{/if}" onclick="toggleBookmark(this, '{$item['id']}', '{$item['cookie']}')"></a>
  <h2>{$item['name']}</h2>
</div>

{$hours = array()}
{foreach $item['hours'] as $entry}
  {capture name="title" assign="title"}
    <div class="label">{$entry['label']}</div>
    <div class="value">{$entry['title']}</div>
  {/capture}
  {$entry['title'] = $title}
  {$entry['label'] = null}
  {$hours[] = $entry}
{/foreach}
{include file="findInclude:common/navlist.tpl" navlistItems=$hours accessKey=false labelColon=false}

{include file="findInclude:common/footer.tpl"}
