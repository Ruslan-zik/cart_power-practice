{* <{if $single_line}span{else}p{/if} {if !$clone}id="{$holder}_{$user_id}" {/if}class="cm-js-item{if $clone} cm-clone hidden{/if}">
{if !$first_item && $single_line}
    <span class="cm-comma{if $clone} hidden{/if}">,&nbsp;&nbsp;</span>
{/if}
{$user_name}(<a href="{"profiles.update?user_id=`$user_id`"|fn_url}" class="underlined user-email"><span>{$email}</span></a>)
{if !$view_only}
    {capture name="tools_list"}
        <li>{btn type="list" text=__("remove") onclick="Tygh.$.cePicker('delete_js_item', '{$holder}', '{$user_id}', 'u'); return false;"}</li>
    {/capture}
    {dropdown content=$smarty.capture.tools_list}
{/if}
</{if $single_line}span{else}p{/if}> *}