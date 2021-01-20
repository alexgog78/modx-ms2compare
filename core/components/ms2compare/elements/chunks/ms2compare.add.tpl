<form method="post" class="ms2compare_form {$active ? 'active' : ''}">
    <input type="hidden" name="record_id" value="{$id}">
    <input type="hidden" name="list" value="{$list}">
    <button class="btn btn-outline-success btn-sm" type="submit" name="ms2compare_action" value="add">{'ms2compare_add' | lexicon}</button>
    <button class="btn btn-outline-danger btn-sm" type="submit" name="ms2compare_action" value="remove">{'ms2compare_remove' | lexicon}</button>
</form>
