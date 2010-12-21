{extends file="findExtends:modules/{$moduleID}/locationAndHours.tpl"}

{block name="header"}
    <h2>{$item['name']}</h2>
    {if $item['fullName'] && $item['fullName'] != $item['name']}
      <p class="smallprint">({$item['fullName']})</p>
    {/if}
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
    {foreach $section as $entry}
      <h3>{$entry['label']}</h3>
      <p>
        {if $entry['url']}<a href="{$entry['url']}">{/if}
          {$entry['title']}
        {if $entry['url']}</a>{/if}
      </p>
      {if !$entry@last}<p class="divider"></p>{/if}
    {/foreach}
  </div>{if !$sectionIsLast}<br/>{/if}
{/block}
