<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reception extends Model
{
    use HasFactory,SoftDeletes;

     protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';


    public $fillable = [

    ];

    public function getDesignation()
    {
        return $this->name_fr . ' | ' . $this->name_ar;
    }

    //  put the relation of this Model Here

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_reception')->withPivot(['id','designation', 'quantity', 'qty_livred','qty_reste','price', 'remise', 'total_remise', 'TOTAL_HT', 'TVA', 'TOTAL_TVA', 'TOTAL_TTC', 'unite']);
    }

    /**
     * Get the user that owns the Devis
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    // public function reglements()
    // {
    //     return $this->hasMany(Reglement::class, 'document_id', 'id');
    // }

    /**
     *getters pour recuperer les attribute de type file pour l'utiliser dans le crud
     *
     */

    public function getRowsTable()
    {
        return [
            'ref' => 'ref',
            'HT' => 'ht',
            'TVA' => 'tva',
            'TTTC' => 'tttc',
            'total_restant' => 'total_restant',
            'total_payant' => 'total_payant',
            'status' => 'status',
            'status_date' => 'status_date',
        ];
    }

    /**
     *getters pour recuperer les attribute de type file pour l'utiliser dans le crud
     *
     */

    public function getRowsTableTrashed()
    {
        return [
            'ref' => 'ref',
            'HT' => 'ht',
            'TVA' => 'tva',
            'TTTC' => 'tttc',
            'status' => 'status',
            'status_date' => 'status_date',
        ];
    }

    public function scopeValid($query)
    {
        return $query->where('status', 'Validé');
    }

    public function scopeWait($query)
    {
        return $query->where('status', 'En attente');
    }
}
