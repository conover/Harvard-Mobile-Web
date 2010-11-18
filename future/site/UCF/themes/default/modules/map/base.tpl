{include file="findInclude:common/header.tpl"}

<div class="{{$platform}} {{$pagetype}}" >
{block name="body"}
  Campus Map
{/block}
</div>

{foreach $inlineJavascriptFooterBlocks as $script}
  <script type="text/javascript">
    {$script} 
  </script>
{/foreach}

</div>
</body>
</html>