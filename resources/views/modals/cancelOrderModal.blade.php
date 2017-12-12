<!-- Modal -->
<div class="modal fade" id="cancelOrderModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <form id="cancel_order_form" method="POST">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PATCH">
                <input type="hidden" name="transaction_code" id="transaction_code">
                <input type="hidden" name="customer_id" id="customer_id">
                <input type="hidden" name="status" id="status">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cancel Order</h4>
                </div>
                <div class="modal-body">
                    <p><b>Enter reason of cancelling the order:</b></p>
                    <textarea id="cancel_reason" name="cancel_reason" style="resize:none" cols="58" rows="2"></textarea></td>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button class="btn btn-danger" type="submit">Continue</button>
                </div>

            </form>
        </div>

    </div>
</div>