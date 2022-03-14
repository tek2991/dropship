<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'log_sheet_id' => $this->log_sheet_id,
            'invoice_no' => $this->invoice_no,
            'date' => $this->date,
            'client_id' => $this->client_id,
            'gross_weight' => $this->gross_weight,
            'no_of_packs' => $this->no_of_packs,
            'is_delivered' => $this->is_delivered ? true : false,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by ?  new UserResource($this->updatedByUser) : null,
            'client' => $this->clientUser ? new UserResource($this->clientUser) : null,
            'images' => $this->images ? ImageResource::collection($this->images) : null,
        ];
    }
}