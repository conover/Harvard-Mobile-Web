{extends file="findExtends:common/base.tpl"}

{block name="body"}

<div class="text">
	<h2>Customize</h2>
	<div class="block">
		Drag the modules listed to customize their order on your homepage, and toggle the checkboxes to add or remove them. Your changes will be automatically saved.
	</div>
</div> 

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

<ul class="gloss seperate">
	<li class="arrow-back"><a href="../home/">Return to Home</a></li>
</ul>

{/block}
