<?php

namespace App\Models;


use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delivery extends Model
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


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }


//  put the relation of this Model Here
/**
 * Get the user that owns the Delivery
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
 */
public function client()
{
    return $this->belongsTo(Client::class, 'client_id', 'id');
}

public function driver()
{
    return $this->belongsTo(Driver::class, 'deliverer', 'id');
}

public function car()
{
    return $this->belongsTo(Car::class, 'delivery_method', 'id');
}

public function command()
{
    return $this->belongsTo(Command::class, 'command_id', 'id');
}
    /**
     *getters pour recuperer les attribute de type file pour l'utiliser dans le crud
    *
    */

    public function getRowsTable(){
        return [
            'client_id' => 'client_id',
            'command_id' => 'command_id',
             'deliverer' => 'deliverer',
             'delivery_method' => 'delivery_method',
             'delivery_date' => 'delivery_date',

         ];
     }

     /**
      *getters pour recuperer les attribute de type file pour l'utiliser dans le crud
     *
     */

     public function getRowsTableTrashed(){
         return [
            'client_id' => 'client_id',
            'command_id' => 'command_id',
             'deliverer' => 'deliverer',
             'delivery_method' => 'delivery_method',
             'delivery_date' => 'delivery_date',
          ];
          }




}
