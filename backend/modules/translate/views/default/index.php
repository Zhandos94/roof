<div class="translate-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
        The action belongs to the controller "<?= get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
    <p>
        You may configure module in application config file:<br>
        <code>'defaultRoute' => 'source-message'</code> <br>
        <code>'gridview' =>  [
            'class' => '\kartik\grid\Module'],</code>
    </p>

    <p>
        Import from Excel configurable in module config file, for example as:<br>
        <code>
            'message_id' => 'A',
            'message_category' => 'B',
            'message' => 'C',
            'message_kz' => 'D',
            'message_ru' => 'E',
            'message_en' => 'F',
        </code>
    </p>
</div>
