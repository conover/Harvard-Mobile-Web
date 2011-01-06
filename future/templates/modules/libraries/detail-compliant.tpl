{extends file="findExtends:modules/{$moduleID}/detail.tpl"}

{block name="header"}
    <a id="bookmark" class="{if $item['bookmarked']}bookmarked{/if}" onclick="toggleBookmark(this, '{$item['id']}', '{$item['cookie']}')"></a>
    <h2>{$item['title']}{if $item['nonLatinTitle']} ({$item['nonLatinTitle']}){/if}</h2>
    <div class="smallprint">
      {if $item['creator']}<br/><a class="authorLink" href="{$item['creatorURL']}">{$item['creator']}</a>{/if}
      {if $item['nonLatinCreator']} ({$item['nonLatinCreator']}){/if}
      {if $item['edition']}<br/>{$item['edition']}{/if}
      {if $item['date'] || $item['publisher']}<br/>{$item['publisher']} {$item['date']}{/if}
      {if ($item['formatDesc'] || $item['type']) && $item['format']|lower != 'image'}
        <br/>{if $item['formatDesc']}{$item['formatDesc']|capitalize}{if strlen($item['type'])}:{/if}{/if}
        {if strlen($item['type'])}{$item['type']}{/if}
      {/if}
      {if $item['workType']}<br/>Work Type: {$item['workType']}{/if}
      {if $item['thumbnail']}
        {if $item['id']}<br/>HOLLIS #: {$item['id']}{/if}
        <div class="thumbnail">
          <div class="smallprint">1 of {$item['imageCount']} images</div>
          {if $item['fullImageUrl']}<a href="{$item['fullImageUrl']}">{/if}
            <img src="{$item['thumbnail']}" alt="{$item['title']} thumbnail image" />
          {if $item['fullImageUrl']}<br/><span class="smallprint">(click for full image)</span></a>{/if}
        </div>
      {/if}
    </div>
{/block}
