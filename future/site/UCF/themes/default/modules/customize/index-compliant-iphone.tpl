{extends file="findExtends:common/base.tpl"}

{block name="body"}
<ul class="gloss">
	<li class="arrow-back"><a href="../home/">Return to Home</a></li>
</ul>

<ul class="gloss" id="dragReorderList">
  {foreach $modules as $id => $info}
    <li>
      <a name="{$id}"></a>
      <input type="checkbox" name="{$id}" checked="true" value="" {if !$info['disableable']}class="required prefs_{$moduleID}"{/if} />
      <a class="title" href="../{$id}/">
        {$info['title']}
      </a>
      <div class="draghandle"></div>
    </li>
  {/foreach}
</ul>
<p id="savedMessage">Saved</p>

{/block}
