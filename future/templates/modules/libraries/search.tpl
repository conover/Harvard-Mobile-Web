{include file="findInclude:common/header.tpl"}

{if $advancedSearch}
  <div class="nonfocal">
    <form method="get" action="{$page}.php">
      <table class="search" width="100%">
        <tr>
          <th align="right" width="6em"><label for="keywords">Keywords:</label></th>
          <td><input type="text" id="keywords" name="keywords" value="{$keywords}" /></td>
        </tr>
        <tr>
          <th align="right" width="6em"><label for="title">Title:</label></th>
          <td><input type="text" id="title" name="title" value="{$title}" /></td>
        </tr>
        <tr>
          <th align="right" width="6em"><label for="author">Author:</label></th>
          <td><input type="text" id="author" name="author" value="{$author}" /></td>
        </tr>
        <tr>
          <th align="right" width="6em"><label for="format">Format:</label></th>
          <td><select id="format" name="format"">
            {foreach $formats as $key => $value}
              <option value="{$key}"{if strval($key) == $format} selected="selected"{/if}>{$value}</option>
            {/foreach}
          </select></td>
        </tr>
        <tr>
          <th align="right" width="6em"><label for="location">Location:</label></th>
          <td><select id="location" name="location">
            {foreach $locations as $key => $value}
              <option value="{$key}"{if strval($key) == $location} selected="selected"{/if}>{$value}</option>
            {/foreach}
           </select></td>
        </tr>
      </table>
      <input type="hidden" name="advanced" value="1" />
      {foreach $breadcrumbSamePageArgs as $arg => $value}
        <input type="hidden" name="{$arg}" value="{$value}" />
      {/foreach}
      
      <p class="formbuttons">
        <span class="formbuttonclose">
          <input type="submit" class="formbutton" value="Search Libraries" />
        </span>
      </p>
   </form>
  </div>
  
  {if $keywords || $title || $author}
    <div class="nonfocal">
      {if $resultCount > 0}
        <p>{$resultCount} match{if $resultCount != 1}es{/if} found</p>
      {/if}
    </div>
  {/if}
{else}
  {include file="findInclude:common/search.tpl" emphasized=false placeholder="Search" resultCount=$resultCount inlineSearchError=$searchError}
{/if}


{if !$advancedSearch || $keywords || $title || $author}
  {include file="findInclude:modules/{$moduleID}/itemlist.tpl" items=$results}
{/if}

{include file="findInclude:common/footer.tpl"}
