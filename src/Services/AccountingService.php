<?php

namespace CodGlo\CGAccounting\Services;

use CodGlo\CGAccounting\Models\Account;
use CodGlo\CGAccounting\Models\Record;

class AccountingService
{
   /**
     * Credit an amount to an account.
     *
     * @param  string  $facc  The from account identifier
     * @param  string  $tacc  The to account identifier
     * @param  float  $amount  The amount to credit
     * @param  string|null  $ref_id  Optional reference ID
     * @param  string|null  $ref_type  Optional reference type
     * @param  string|null  $description  Optional description of the transaction
     * @return bool|string Returns true if successful, otherwise an error message
     */
    public function credit($facc, $tacc, $amount, $ref_id = null, $ref_type = null, $description = null)
    {
        $from_account = Account::where('name', $facc)->first();
        $to_account = Account::where('name', $tacc)->first();

        if (!$from_account || !$to_account) {
            return 'Accounts not found';
        }

        $from_account_id = $from_account->id;
        $from_account_type = $from_account->type;
        $from_account_last_row = Record::where('from_account', $from_account_id)->orderBy('id', 'desc')->first();
        $from_account_last_row_balance = $from_account_last_row->balance ?? 0;

        if ($from_account_type == 'assets' || $from_account_type == 'expenses') {
            $new_balance = $from_account_last_row_balance - $amount;

            $new_entry = new Record();
            $new_entry->from_account = $from_account_id;
            $new_entry->to_account = $to_account->id;
            $new_entry->debit = 0;
            $new_entry->credit = $amount;
            $new_entry->balance = $new_balance;
            $new_entry->ref_id = $ref_id;
            $new_entry->ref_type = $ref_type;
            $new_entry->description = $description;
            $new_entry->save();

            return true;
        } elseif (in_array($from_account_type, ['liabilities', 'equity', 'income'])) {
            $new_balance = $from_account_last_row_balance + $amount;

            $new_entry = new Record();
            $new_entry->from_account = $from_account_id;
            $new_entry->to_account = $to_account->id;
            $new_entry->debit = 0;
            $new_entry->credit = $amount;
            $new_entry->balance = $new_balance;
            $new_entry->ref_id = $ref_id;
            $new_entry->ref_type = $ref_type;
            $new_entry->description = $description;
            $new_entry->save();

            return true;
        }

        return 'Invalid account type';
    }

    /**
     * Debit an amount from an account.
     *
     * @param  string  $facc  The from account identifier
     * @param  string  $tacc  The to account identifier
     * @param  float  $amount  The amount to debit
     * @param  string|null  $ref_id  Optional reference ID
     * @param  string|null  $ref_type  Optional reference type
     * @param  string|null  $description  Optional description of the transaction
     * @return bool|string Returns true if successful, otherwise an error message
     */
    public function debit($facc, $tacc, $amount, $ref_id = null, $ref_type = null, $description = null)
    {
        $from_account = Account::where('name', $facc)->first();
        $to_account = Account::where('name', $tacc)->first();

        if (!$from_account || !$to_account) {
            return 'Accounts not found';
        }

        $from_account_id = $from_account->id;
        $from_account_type = $from_account->type;
        $from_account_last_row = Record::where('from_account', $from_account_id)->orderBy('id', 'desc')->first();
        $from_account_last_row_balance = $from_account_last_row->balance ?? 0;

        if ($from_account_type == 'assets' || $from_account_type == 'expenses') {
            $new_balance = $from_account_last_row_balance + $amount;

            $new_entry = new Record();
            $new_entry->from_account = $from_account_id;
            $new_entry->to_account = $to_account->id;
            $new_entry->debit = 0;
            $new_entry->credit = $amount;
            $new_entry->balance = $new_balance;
            $new_entry->ref_id = $ref_id;
            $new_entry->ref_type = $ref_type;
            $new_entry->description = $description;
            $new_entry->save();

            return true;
        } elseif (in_array($from_account_type, ['liabilities', 'equity', 'income'])) {
            $new_balance = $from_account_last_row_balance - $amount;

            $new_entry = new Record();
            $new_entry->from_account = $from_account_id;
            $new_entry->to_account = $to_account->id;
            $new_entry->debit = 0;
            $new_entry->credit = $amount;
            $new_entry->balance = $new_balance;
            $new_entry->ref_id = $ref_id;
            $new_entry->ref_type = $ref_type;
            $new_entry->description = $description;
            $new_entry->save();

            return true;
        }

        return 'Invalid account type';
    }
}