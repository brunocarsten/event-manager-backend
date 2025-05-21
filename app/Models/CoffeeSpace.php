<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoffeeSpace extends Model
{
    use HasFactory;
    protected $table = 'coffee_spaces';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'capacity'];

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }
}
