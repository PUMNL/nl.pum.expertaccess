{if (!empty($link_to_portal_profile))}
    {capture assign="portal_profile_button"}{strip}
    <li class="crm-myportalprofile-action crm-contact-portal">
        <a href="{$link_to_portal_profile}" class="portal button" title="Edit my profile">
            <span>{ts}Edit my profile{/ts}</span>
        </a>
    </li>
    {/strip}{/capture}


    <script type="text/javascript">
    {literal}
    cj(function() {
    cj('li.crm-summary-block').after('{/literal}{$portal_profile_button}{literal}');
    }); 
    {/literal}
    </script>
{/if}