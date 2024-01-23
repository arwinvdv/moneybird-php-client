<?php

namespace Picqer\Financials\Moneybird\Entities;

use Picqer\Financials\Moneybird\Actions\FindAll;
use Picqer\Financials\Moneybird\Actions\FindOne;
use Picqer\Financials\Moneybird\Actions\Removable;
use Picqer\Financials\Moneybird\Actions\Storable;
use Picqer\Financials\Moneybird\Model;

/**
 * Class LedgerAccount.
 */
class LedgerAccount extends Model
{
    use FindAll, FindOne, Storable, Removable;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'account_type',
        'account_id',
        'parent_id',
        'allowed_document_types',
        'created_at',
        'updated_at',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'ledger_accounts';

    /**
     * @var string
     */
    protected $namespace = 'ledger_account';

    /**
     * Dirty fix for allowed_document_types, which is an object because of JSON_FORCE_OBJECT but should be an array.
     * TODO: make a generic solution for this.
     *
     * @return string
     */
    public function jsonWithNamespace()
    {
        $json = parent::jsonWithNamespace();
        $array = json_decode($json, true);

        // Make sure allowed_document_types is always an array and not an object
        if (isset($array['ledger_account']['allowed_document_types']) && is_object($array['ledger_account']['allowed_document_types'])) {
            $array['ledger_account']['allowed_document_types'] = (array) $array['ledger_account']['allowed_document_types'];
        }
        return json_encode($array);
    }

}
