<?php

namespace Picqer\Financials\Moneybird\Entities;

use Picqer\Financials\Moneybird\Actions\Filterable;
use Picqer\Financials\Moneybird\Actions\FindAll;
use Picqer\Financials\Moneybird\Actions\FindOne;
use Picqer\Financials\Moneybird\Actions\Noteable;
use Picqer\Financials\Moneybird\Actions\Removable;
use Picqer\Financials\Moneybird\Actions\Search;
use Picqer\Financials\Moneybird\Actions\Storable;
use Picqer\Financials\Moneybird\Actions\Synchronizable;
use Picqer\Financials\Moneybird\Entities\Generic\Event;
use Picqer\Financials\Moneybird\Exceptions\ApiException;
use Picqer\Financials\Moneybird\Model;

/**
 * Class Contact.
 *
 * @property string $id
 * @property int|string $administration_id
 * @property string $company_name
 * @property ?string $firstname
 * @property string $lastname
 * @property string $address1
 * @property string $address2
 * @property string $zipcode
 * @property string $city
 * @property string $country
 * @property string $phone
 * @property string $delivery_method
 * @property string $customer_id
 * @property string $tax_number
 * @property string $chamber_of_commerce
 * @property string $bank_account
 * @property string $attention
 * @property string $email
 * @property bool $email_ubl
 * @property string $send_invoices_to_attention
 * @property string $send_invoices_to_email
 * @property string $send_estimates_to_attention
 * @property string $send_estimates_to_email
 * @property bool $sepa_active
 * @property string $sepa_iban
 * @property string $sepa_iban_account_name
 * @property string $sepa_bic
 * @property string $sepa_mandate_id
 * @property ?string $sepa_mandate_date
 * @property string $sepa_sequence_type
 * @property string $credit_card_number
 * @property string $credit_card_reference
 * @property string $credit_card_type
 * @property ?string $tax_number_validated_at
 * @property ?string $tax_number_valid
 * @property ?string $invoice_workflow_id
 * @property ?string $estimate_workflow_id
 * @property string $si_identifier
 * @property ?string $si_identifier_type
 * @property bool $moneybird_payments_mandate
 * @property string $created_at
 * @property string $updated_at
 * @property int $version
 * @property string $sales_invoices_url
 * @property Note[] $notes
 * @property ContactCustomField[] $custom_fields
 * @property ContactPeople[] $contact_people
 * @property bool $archived
 * @property Event[] $events
 */
class Contact extends Model
{
    use Search, FindAll, FindOne, Storable, Removable, Filterable, Synchronizable, Noteable;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'company_name',
        'firstname',
        'lastname',
        'attention',
        'address1',
        'address2',
        'zipcode',
        'city',
        'country',
        'email',
        'phone',
        'delivery_method',
        'customer_id',
        'tax_number',
        'chamber_of_commerce',
        'bank_account',
        'moneybird_payments_mandate',
        'send_invoices_to_attention',
        'send_invoices_to_email',
        'send_estimates_to_attention',
        'send_estimates_to_email',
        'sepa_active',
        'sepa_iban',
        'sepa_iban_account_name',
        'sepa_bic',
        'sepa_mandate_id',
        'sepa_mandate_date',
        'sepa_sequence_type',
        'credit_card_number',
        'credit_card_reference',
        'credit_card_type',
        'invoice_workflow_id',
        'estimate_workflow_id',
        'email_ubl',
        'tax_number_validated_at',
        'created_at',
        'updated_at',
        'notes',
        'custom_fields',
        'contact_people',
        'version',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'contacts';

    /**
     * @var string
     */
    protected $namespace = 'contact';

    /**
     * @var string
     */
    protected $filter_endpoint = 'contacts/filter';

    /**
     * @var array
     */
    protected $multipleNestedEntities = [
        'custom_fields' => [
            'entity' => ContactCustomField::class,
            'type' => self::NESTING_TYPE_NESTED_OBJECTS,
        ],
        'contact_people' => [
            'entity' => ContactPeople::class,
            'type' => self::NESTING_TYPE_NESTED_OBJECTS,
        ],
    ];

    /**
     * @param  string|int  $customerId
     * @return static
     *
     * @throws ApiException
     */
    public function findByCustomerId($customerId)
    {
        $result = $this->connection()->get($this->getEndpoint() . '/customer_id/' . urlencode($customerId));

        return $this->makeFromResponse($result);
    }

    /**
     * @throws ApiException
     */
    public function getPaymentsMandate(): array
    {
        return $this->connection()->get(
            $this->getEndpoint() . '/' . $this->id . '/moneybird_payments_mandate'
        );
    }

    public function addContactPerson(array $attributes): ContactPeople
    {
        $attributes['contact_id'] = $this->id;
        $contactPerson = new ContactPeople($this->connection(), $attributes);

        return $contactPerson->save();
    }
}
