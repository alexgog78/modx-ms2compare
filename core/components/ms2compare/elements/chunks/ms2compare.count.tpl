<div class="ms2compare_link {$total_count > 0 ? 'full' : ''}">
    <div class="empty">
        {'ms2compare' | lexicon}: <strong>{'ms2compare_is_empty' | lexicon}</strong>
    </div>
    <div class="not_empty dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">{'ms2compare' | lexicon}: <strong class="ms2compare_count">{$total_count}</strong></a>
        <ul class="dropdown-menu">
            {foreach $lists as $list => $count}
                <li><a href="{$link}">{('ms2compare_list_' ~ $list) | lexicon}: <strong class="ms2compare_count_{$list}">{$count}</strong></a></li>
            {/foreach}
        </ul>
    </div>
</div>
