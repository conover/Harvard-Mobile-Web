{extends file="findExtends:common/base.tpl"}

{block name="body"}

<div class="text">
	<h2>Customize</h2>
	<div class="block">
		Use the arrow buttons to customize the order of icons on your homepage, and the checkboxes to toggle visibility. Your changes will be automatically saved.
	</div>
</div> 

<ul class="gloss nav iconic" id="homepageList">
  {foreach $modules as $id => $info}
    <li id="{$id}">
      {if $info['disableable']}
        <input id="toggle_{$id}" type="checkbox" onclick="toggle(this);"{if !$info['disabled']} checked="checked"{/if} />
      {/if}
      <span class="nolink">
        <label for="toggle_{$id}">{$info['title']}</label>
        <span class="nolinkbuttons">
          <a href="#" onclick="moveUp(this); return false;">
            <!--<img src="/modules/{$moduleID}/images/button-up.png" width="26" height="26" class="moveup" alt="Move up"/>-->
            <div class="moveup">&nbsp;</div>
          </a> 
          <a href="#" onclick="moveDown(this); return false;">
            <!--<img src="/modules/{$moduleID}/images/button-down.png" width="26" height="26" class="movedown" alt="Move down"/>-->
            <div class="movedown">&nbsp;</div>
          </a>
        </span> 
      </span>                   
    </li>
  {/foreach}
</ul>
<ul class="gloss">
	<li class="arrow-back"><a href="../home/">Return to Home</a></li>
</ul>
{/block}

