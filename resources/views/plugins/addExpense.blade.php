<style>
.plugin div.sum {
  margin-left: 1rem;
}
.plugin input {
    width: 6rem;
}
</style>
<script>
$(function() {
    $('span[expense]').click(function () {
        var expense = $(this).attr('expense');
        var url = '{{ route('browse.plugin', ['classId' => $element->getClassId(), 'method' => 'add']) }}';
        
        $.blockUI();
        
        $.post(url, {
            expense: expense
        }, function(data) {
            $.unblockUI();
            
            document.location.href = document.location.href;
        }).fail(function() {
            $.unblockUI();
            
            $.alertDefaultError();
        });
        
        return false;
    });
});
</script>
<div class="right sum">
    <input type="text" name="add_expense_sum" value="" placeholder="Сумма, руб.">
</div>
Добавить расход: <span class="dashed hand" expense="sape">Sape</span>,
<span class="dashed hand" expense="guzh">Гужвинская</span>,
<span class="dashed hand" expense="potu">Потуданский</span>