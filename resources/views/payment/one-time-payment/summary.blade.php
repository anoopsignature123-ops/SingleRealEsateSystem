<div class="col-lg-5">
    <div class="one-time-summary-card sticky-top">
        <div class="one-time-summary-loader d-none" id="one_time_summary_loader">
            <div class="one-time-loader-box">
                <span class="spinner-border spinner-border-sm text-success" role="status" aria-hidden="true"></span>
                <strong>Loading booking details...</strong>
            </div>
        </div>

        <div class="one-time-summary-head">
            <span class="one-time-summary-icon">
                <i class="bi bi-receipt"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-1 text-dark">Payment Summary</h4>
                <small class="text-muted">Confirmed, hold and due details</small>
            </div>
        </div>

        <div class="one-time-summary-grid">
            <div class="one-time-summary-box">
                <small>Total Cost</small>
                <strong>&#8377;<span id="total_cost">0.00</span></strong>
            </div>

            <div class="one-time-summary-box success">
                <small>Confirmed Paid</small>
                <strong>&#8377;<span id="total_paid">0.00</span></strong>
            </div>

            <div class="one-time-summary-box warning">
                <small>Hold Amount</small>
                <strong>&#8377;<span id="hold_amount">0.00</span></strong>
            </div>

            <div class="one-time-summary-box danger">
                <small>Due Amount</small>
                <strong>&#8377;<span id="due_amount">0.00</span></strong>
                <button type="button" class="btn btn-sm btn-outline-danger mt-2 w-100 d-none" id="fill_due_amount">
                    <i class="bi bi-cash-stack me-1"></i> Pay Full Due Amount
                </button>
            </div>
        </div>

        <div class="one-time-history-box">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold mb-0">Payment History</h6>
                <span class="badge bg-light text-dark border" id="payment_history_count">0 Records</span>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0 one-time-history-table">
                    <thead class="table-light">
                        <tr>
                            <th>Receipt</th>
                            <th>Plot</th>
                            <th>Date</th>
                            <th>Paid</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="payment_history">
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">No Payment Found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
