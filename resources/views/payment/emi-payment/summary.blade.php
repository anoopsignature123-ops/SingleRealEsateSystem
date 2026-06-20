<div class="col-lg-4">
    <div class="emi-summary-card sticky-top">
        <div class="emi-summary-loader d-none" id="emi_summary_loader">
            <div class="emi-loader-box">
                <span class="spinner-border spinner-border-sm text-success" role="status" aria-hidden="true"></span>
                <strong>Loading EMI details...</strong>
            </div>
        </div>

        <div class="emi-summary-head">
            <span class="emi-summary-icon">
                <i class="bi bi-calendar2-check"></i>
            </span>
            <div>
                <h4 class="fw-bold mb-1 text-dark">EMI Summary</h4>
                <small class="text-muted">Installment and payment details</small>
            </div>
        </div>

        <div class="emi-summary-grid">
            <div class="emi-summary-box">
                <small>Total Plot Cost</small>
                <strong>&#8377;<span id="total_cost">0.00</span></strong>
            </div>

            <div class="emi-summary-box primary">
                <small>Booking Amount</small>
                <strong>&#8377;<span id="booking_amount">0.00</span></strong>
            </div>

            <div class="emi-summary-box success">
                <small>Confirmed Paid</small>
                <strong>&#8377;<span id="total_paid">0.00</span></strong>
            </div>

            <div class="emi-summary-box warning">
                <small>Hold Amount</small>
                <strong>&#8377;<span id="hold_amount">0.00</span></strong>
            </div>

            <div class="emi-summary-box info">
                <small>Monthly EMI</small>
                <strong>&#8377;<span id="monthly_emi">0.00</span></strong>
                <button type="button" class="btn btn-sm btn-outline-info mt-2 w-100 d-none" id="fill_monthly_emi">
                    <i class="bi bi-calendar-check me-1"></i> Pay Current EMI
                </button>
            </div>

            <div class="emi-summary-box danger">
                <small>Due Amount</small>
                <strong>&#8377;<span id="due_amount">0.00</span></strong>
                <button type="button" class="btn btn-sm btn-outline-danger mt-2 w-100 d-none" id="fill_due_amount">
                    <i class="bi bi-cash-stack me-1"></i> Pay Full Due
                </button>
            </div>
        </div>

        <div class="emi-progress-box mb-3">
            <div>
                <small>EMI Start Date</small>
                <strong id="emi_start_date">-</strong>
            </div>
            <div>
                <small>EMI Progress</small>
                <strong id="emi_months">0 / 0 Months</strong>
            </div>
        </div>

        <div class="emi-history-box">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold mb-0">Payment History</h6>
                <span class="badge bg-light text-dark border" id="payment_history_count">0 Records</span>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Receipt</th>
                            <th>Date</th>
                            <th>Paid</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="payment_history">
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">No Payment Found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
