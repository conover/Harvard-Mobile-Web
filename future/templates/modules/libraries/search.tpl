{include file="findInclude:common/header.tpl"}

{include file="findInclude:common/search.tpl" emphasized=false placeholder="Search" resultCount=$resultCount inlineSearchError=$searchError}

{foreach $results as $i => $result}
  {capture name="title" assign="title"}
    <div class="ellipsis_wrapper">
      <div class="ellipsis" id="ellipsis_{$i}">{$result@iteration}. {$result['title']}</div>
      <div class="smallprint"><img src="/modules/{$moduleID}/images/{$result['format']}.png" alt="{$result['format']}" width="16" height="16" /> {$result['date']} | {$result['creator']}</div>
    </div>
  {/capture}
  {$results[$i]['title'] = $title}
{/foreach}

{include file="findInclude:common/results.tpl" results=$results}

{include file="findInclude:common/footer.tpl"}
