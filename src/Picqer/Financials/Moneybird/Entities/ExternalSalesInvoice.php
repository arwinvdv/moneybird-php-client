<?php

namespace Picqer\Financials\Moneybird\Entities;

use Picqer\Financials\Moneybird\Actions\Attachment;
use Picqer\Financials\Moneybird\Actions\Downloadable;
use Picqer\Financials\Moneybird\Actions\Filterable;
use Picqer\Financials\Moneybird\Actions\FindAll;
use Picqer\Financials\Moneybird\Actions\FindOne;
use Picqer\Financials\Moneybird\Actions\Removable;
use Picqer\Financials\Moneybird\Actions\Storable;
use Picqer\Financials\Moneybird\Actions\Synchronizable;
use Picqer\Financials\Moneybird\Connection;
use Picqer\Financials\Moneybird\Entities\Generic\Event;
use Picqer\Financials\Moneybird\Exceptions\ApiException;
use Picqer\Financials\Moneybird\Model;

/**
 * Class ExternalSalesInvoice.
 *
 * @property string $id
 * @property int|string $administration_id
 * @property ?string $contact_id
 * @property Contact $contact
 * @property string $date
 * @property string $state
 * @property ?string $due_date
 * @property string $reference
 * @property int $entry_number
 * @property ?string $origin
 * @property ?string $source
 * @property ?string $source_url
 * @property string $currency
 * @property ?string $paid_at
 * @property string $created_at
 * @property string $updated_at
 * @property int $version
 * @property ExternalSalesInvoiceDetail[] $details
 * @property ExternalSalesInvoicePayment[] $payments
 * @property string $total_paid
 * @property string $total_unpaid
 * @property string $total_unpaid_base
 * @property bool $prices_are_incl_tax
 * @property string $total_price_excl_tax
 * @property string $total_price_excl_tax_base
 * @property string $total_price_incl_tax
 * @property string $total_price_incl_tax_base
 * @property ?string $marked_dubious_on
 * @property ?string $marked_uncollectible_on
 * @property Note[] $notes
 * @property ExternalSalesInvoiceAttachment[] $attachments
 * @property Event[] $events
 * @property array $tax_totals
 *
 */
class ExternalSalesInvoice extends Model
{
    use FindAll, FindOne, Storable, Removable, Filterable, Downloadable, Synchronizable, Attachment;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'contact_id',
        'reference',
        'date',
        'due_date',
        'entry_number',
        'state',
        'currency',
        'prices_are_incl_tax',
        'source',
        'source_url',
        'origin',
        'paid_at',
        'total_paid',
        'total_unpaid',
        'total_price_excl_tax',
        'total_price_excl_tax_base',
        'total_price_incl_tax',
        'total_price_incl_tax_base',
        'created_at',
        'updated_at',
        'details',
        'payments',
        'notes',
        'attachments',
        'version',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'external_sales_invoices';

    /**
     * @var string
     */
    protected $namespace = 'external_sales_invoice';

    /**
     * @var array
     */
    protected $singleNestedEntities = [
        'contact' => Contact::class,
    ];

    /**
     * @var array
     */
    protected $multipleNestedEntities = [
        'attachments' => [
            'entity' => ExternalSalesInvoiceAttachment::class,
            'type' => self::NESTING_TYPE_ARRAY_OF_OBJECTS,
        ],
        'details' => [
            'entity' => ExternalSalesInvoiceDetail::class,
            'type' => self::NESTING_TYPE_ARRAY_OF_OBJECTS,
        ],
        'payments' => [
            'entity' => ExternalSalesInvoicePayment::class,
            'type' => self::NESTING_TYPE_ARRAY_OF_OBJECTS,
        ],
    ];

    public function __construct(Connection $connection, array $attributes = [])
    {
        parent::__construct($connection, $attributes);

        $this->attachmentPath = 'attachment';
    }

    /**
     * Register a payment for the current external sales invoice.
     *
     * @param  ExternalSalesInvoicePayment  $externalSalesInvoicePayment  (payment_date and price are required)
     * @return ExternalSalesInvoicePayment
     *
     * @throws ApiException
     */
    public function registerPayment(ExternalSalesInvoicePayment $externalSalesInvoicePayment)
    {
        if (! isset($externalSalesInvoicePayment->payment_date)) {
            throw new ApiException('Required [payment_date] is missing');
        }

        if (! isset($externalSalesInvoicePayment->price)) {
            throw new ApiException('Required [price] is missing');
        }

        $result = $this->connection()->post($this->endpoint . '/' . $this->id . '/payments',
            $externalSalesInvoicePayment->jsonWithNamespace()
        );

        $newPayment = new ExternalSalesInvoicePayment($this->connection(), $result);
        $this->payments = [...$this->payments ?? [], $newPayment];

        return $newPayment;
    }

    /**
     * Delete a payment for the current external sales invoice.
     *
     * @param  ExternalSalesInvoicePayment  $externalSalesInvoicePayment  (id is required)
     * @return $this
     *
     * @throws ApiException
     */
    public function deletePayment(ExternalSalesInvoicePayment $externalSalesInvoicePayment)
    {
        if (! isset($externalSalesInvoicePayment->id)) {
            throw new ApiException('Required [id] is missing');
        }

        $this->connection()->delete($this->endpoint . '/' . $this->id . '/payments/' . $externalSalesInvoicePayment->id);

        return $this;
    }
}
