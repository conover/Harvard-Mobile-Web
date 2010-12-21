{extends file="findExtends:modules/{$moduleID}/fullHours.tpl"}

{block name="header"}
    {$libraryName}
  </div>
  <div class="focal">
    <img src="/common/images/bookmark-{if $item['bookmarked']}on{else}off{/if}.gif" alt="" />
    <a id="bookmark" href="{$bookmarkURL}">
      {if $item['bookmarked']}Remove bookmark{else}Bookmark this {$item['type']}{/if}
    </a>
{/block}

{block name="item"}
{/block}

{block name="itemList"}
  <div class="focal">
    {foreach $item['hours'] as $entry}
      <h3>{$entry['label']}</h3>
      <p>{$entry['title']}</p>
      {if !$entry@last}<p class="divider"></p>{/if}
    {/foreach}
  </div>
{/block}
