{extends file="findExtends:common/base.tpl"}

{block name="body"}
<div>
	<h2>FAQs</h2>
	<form action="" method="get"><div>
		<input type="search" placeholder="Ask a question" name="q" />
		<input type="submit" value="Search" />
	</div></form>
	{if $items}
	<ul class="gloss">
	{foreach $items as $item}
		<li class="arrow"><a href="answer.php?url={urlencode($item->getLink())}&amp;q={$q}">
			{$item->getTitle()}
		</a></li>
	{/foreach}
	</ol>
	{else if $q}
	<p>No help found for '{$q}'.</p>
	{else}
		
	{/if}
</div>
{/block}
http://ucf.custhelp.com/cgi-bin/ucf.cfg/php/enduser/std_adp.php?p_faqid=549&p_created=1015631783
http://ucf.custhelp.com/cgi-bin/ucf.cfg/php/enduser/prnt_adp.php?p_faqid=549&p_created=1015631783