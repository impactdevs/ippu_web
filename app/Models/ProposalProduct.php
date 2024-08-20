<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalProduct extends Model
{
    use HasFactory;

    /**
     * ProposalProduct belongs to Product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        // belongsTo(RelatedModel, foreignKey = product_id, keyOnRelatedModel = id)
        return $this->belongsTo(ProductService::class);
    }
}
