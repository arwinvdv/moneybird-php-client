<?php

namespace Picqer\Financials\Moneybird\Entities\Generic;

use Picqer\Financials\Moneybird\Model;

/**
 * Class InvoicePayment.
 *
 * @property string $id
 * @property int|string $administration_id
 * @property string $invoice_type
 * @property string $invoice_id
 * @property string $financial_account_id
 * @property int|string $user_id
 * @property ?string $payment_transaction_id
 * @property string $price
 * @property string $price_base
 * @property string $payment_date
 * @property ?string $credit_invoice_id
 * @property string $financial_mutation_id
 * @property string $transaction_identifier
 * @property string $manual_payment_action
 * @property ?string $linked_payment_id
 * @property string $ledger_account_id
 * @property string $created_at
 * @property string $updated_at
 */
abstract class InvoicePayment extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'invoice_type',
        'invoice_id',
        'financial_account_id',
        'user_id',
        'payment_transaction_id',
        'price',
        'price_base',
        'payment_date',
        'credit_invoice_id',
        'financial_mutation_id',
        'transaction_identifier',
        'manual_payment_action',
        'ledger_account_id',
        'created_at',
        'updated_at',
    ];

    /**
     * @var string
     */
    protected $namespace = 'payment';
}
