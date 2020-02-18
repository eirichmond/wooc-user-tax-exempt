<?php $user_is_vat_exempt = get_user_meta($user->ID, 'user_is_vat_exempt', true); ?>

<table class="form-table">
    <tr>
        <th>
            <label for="user_is_vat_exempt"><?php esc_html_e( 'VAT Exempt' ); ?></label>
        </th>
        <td>
            <label id="user_is_vat_exempt">
                <input type="checkbox" name="user_is_vat_exempt" value="1" <?php if ( $user_is_vat_exempt ) echo 'checked="checked"'; ?> />
                user does not include VAT charges in their account
            </label>

        </td>
    </tr>
</table>
