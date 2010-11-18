{extends file="findExtends:common/header.tpl"}

{block name="additionalHeadTags"}
  <!--  Mobile viewport optimized: j.mp/bplateviewport -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
{/block}

{block name="header"}
    <div id="Header">
      {if isset($topItem)}
          <div id="new"><a href="/about/new.php"><span class="newlabel">NEW:</span>{$topItem}</a></div>
      {/if}
      <h1><a href="/home/">UCF<span>Mobile</span></a></h1>
    </div>
{/block}