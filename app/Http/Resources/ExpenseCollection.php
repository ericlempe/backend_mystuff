<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpenseCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $this->collection->transform(function ($item, $key) {

            $expirationDate = str_pad($item->expiration_day, "2", "0", STR_PAD_LEFT);
            return [
                'id' => $item->id,
                'invoice_id' => $item->invoice_id,
                'expense_id' => $item->expense_id,
                'value' => !is_null($item->value) ? number_format($item->value, 2, ',', '.') : null,
                'paid_in' => $item->paid_in,
                'name' => $item->name,
                'description' => $item->description,
                'expense_type' => $item->expense_type,
                'expiration_day' => date("Y-m-{$expirationDate}"),
            ];
        });

        return [
            'data' => $this->collection,
        ];
    }
}
