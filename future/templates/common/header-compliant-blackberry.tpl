{extends file="findExtends:common/header-compliant.tpl"}

{block name="breadcrumbs"}
  {if !$isModuleHome && $moduleID != 'home'}
    <a href="./" class="moduleicon">
      <img src="/common/images/title-{$navImageID|default:$moduleID}.png"   width="28" height="28" alt="" />
    </a>
  {/if}
{/block}
