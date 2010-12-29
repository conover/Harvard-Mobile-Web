<div class="share">
Share article:
    
    <ul>
        <li>
            {block name="shareImage"}
              <a href="{$shareEmailUrl}"><img src="/common/images/share.png"/></a>Email
            {/block}
        </li>
        {block name="shareOnFacebook"}
            <li>
                <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
                <fb:like href="{$urlToBeShared}" layout="button_count" show_faces="false" width="100" action="recommend"></fb:like>
            </li>
        {/block}
        {block name="shareOnTwitter"}
            <li>
                <a href="http://twitter.com/share?url={$urlToBeShared}&amp;text={$shareRemark}&amp;Via=Harvard">Tweet this</a>         
            </li>
        {/block}
    </ul>
</div>
