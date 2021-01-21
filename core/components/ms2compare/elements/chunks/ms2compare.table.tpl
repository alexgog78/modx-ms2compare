<div class="ms2compare_resources table-responsive">
    <form method="post" class="ms2compare_form">
        <h5>{('ms2compare_list_' ~ $list) | lexicon}: <span class="ms2compare_count_{$list}">{$_modx->getPlaceholder('ms2compare_count_' ~ $list)}</span></h5>
        <input type="hidden" name="list" value="{$list}">
        <button class="btn btn-warning" type="submit" name="ms2compare_action" value="clear">{'ms2compare_clear' | lexicon}</button>
    </form>
    <br>
    <br>
    <table class="ms2compare_table table table-bordered table-hover">
        <thead>
        <tr class="ms2compare_table-row">
            <td class="ms2compare_table-cell_corner">
                <a href="javascript:" class="ms2compare_options-view active" data-view="all">{'ms2compare_options_all' | lexicon}</a>
                <br>
                <a href="javascript:" class="ms2compare_options-view" data-view="diff">{'ms2compare_options_diff' | lexicon}</a>
            </td>
            {foreach $products as $product}
                <th class="ms2compare_table-cell_product ms2compare_resource_{$product.id}" scope="col">
                    <a href="{$product.id | url}">
                        {if $product.thumb?}
                            <img src="{$product.thumb}" class="mw-100" alt="{$product.pagetitle}" title="{$product.pagetitle}">
                        {else}
                            <img src="{'assets_url' | option}components/minishop2/img/web/ms2_small.png" class="mw-100" alt="{$product.pagetitle}" title="{$product.pagetitle}">
                        {/if}
                    </a>
                    <br>
                    <a href="{$product.id | url}" class="font-weight-bold">{$product.pagetitle}</a>
                    <br>
                    <span class="price">{$product.price} {'ms2_frontend_currency' | lexicon}</span>
                    {if $product.old_price?}
                        <br>
                        <span class="old_price">{$product.old_price} {'ms2_frontend_currency' | lexicon}</span>
                    {/if}
                    <br>
                    <form method="post" class="ms2compare_form active">
                        <input type="hidden" name="record_id" value="{$product.id}">
                        <input type="hidden" name="list" value="{$list}">
                        <button class="btn btn-outline-danger btn-sm" type="submit" name="ms2compare_action" value="remove">{'ms2compare_remove' | lexicon}</button>
                    </form>
                </th>
            {/foreach}
        </tr>
        </thead>
        <tbody>
        {foreach $rows as $field => $data index=$index}
            <tr class="ms2compare_table-row {$data.same ? 'same table-success' : ''}">
                <th class="ms2compare_table-cell_field" scope="row">
                    {$index + 1}.
                    {switch $field}
                        {case 'vendor.name'}
                            {'ms2_product_vendor' | lexicon}
                        {default}
                            {('ms2_product_' ~ $field) | lexicon}
                    {/switch}
                </th>
                {foreach $data.values as $id => $value}
                    <td class="ms2compare_table-cell ms2compare_resource_{$id}">
                        {if $value | iterable}
                            {$value | join : '; '}
                        {else}
                            {$value}
                        {/if}
                    </td>
                {/foreach}
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>
